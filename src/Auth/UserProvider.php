<?php

declare(strict_types=1);

namespace vBridgeCloud\CallLoginBundle\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private string $internalLoginUrl,
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $session = $this->requestStack->getSession();
        $loginToken = $session->get('login_token');
        if (! $loginToken) {
            throw new UserNotFoundException();
        }

        try {
            $client = new Client();
            $client->get($this->internalLoginUrl . '/api/token/verify', ['headers' => [
                'Authorization' => 'Bearer ' . $loginToken,
            ]]);
        } catch (RequestException) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return $class === AuthenticatedUser::class;
    }

    public function loadUserByUsername(string $username): void
    {
    }

    public function loadUserByIdentifier(string $identifier): AuthenticatedUser
    {
        throw new RuntimeException('Cannot load it\'s own users');
    }
}
