<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Auth;

use App\Environment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class AuthEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private Environment $environment
    ) {
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->environment->authorizeUrl($request));
    }
}
