<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Auth;

use GuzzleHttp\Client;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

use function assert;
use function is_string;

final class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly string $internalLoginUrl,
        private readonly string $publicLoginUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectPath,
        private readonly RedirectUrlGenerator $redirectUrlGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_auth_index';
    }

    public function authenticate(Request $request): Passport
    {
        if ($request->query->has('code')) {
            $client   = new Client();
            $response = $client->request(
                'POST',
                $this->internalLoginUrl . '/access_token',
                [
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'redirect_uri' => $this->redirectUrlGenerator->generate(),
                        'code' => $request->query->get('code'),
                    ],
                ],
            );

            $response = json_decode((string) $response->getBody(), true);
            $idToken = $response['id_token'];
            $accessToken = $response['access_token'];
        } elseif ($request->query->has('access_token') && $request->query->has('id_token')) {
            $idToken = $request->query->get('id_token');
            $accessToken = $request->query->get('access_token');
        } else {
            throw new BadCredentialsException();
        }

        try {
            $configuration = Configuration::forUnsecuredSigner();
            $token = $configuration->parser()->parse($idToken);
        } catch (Exception) {
            throw new AuthenticationException();
        }

        $userIdentifier = $token->claims()->get('sub');
        assert(is_string($userIdentifier));

        $request->getSession()->set('login_token', $accessToken);

        return new SelfValidatingPassport(new UserBadge(
            $userIdentifier,
            static function () use ($token): UserInterface {
                return new AuthenticatedUser(
                    $token->claims()->get('id'),
                    $token->claims()->get('company_id'),
                    $token->claims()->get('name'),
                    $token->claims()->get('email'),
                    $token->claims()->get('roles'),
                );
            }
        ));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate($this->redirectPath));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->publicLoginUrl . '/login');
    }
}
