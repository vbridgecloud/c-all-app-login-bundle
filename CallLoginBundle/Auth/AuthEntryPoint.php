<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Auth;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class AuthEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly string $publicLoginUrl,
        private readonly string $clientId,
        private readonly string $oauthRedirectPath,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $url = $this->publicLoginUrl
            . '/authorize?'
            . http_build_query([
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->urlGenerator->generate(
                    $this->oauthRedirectPath,
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'scope' => 'openid user-info',
            ]);

        return new RedirectResponse($url);
    }
}
