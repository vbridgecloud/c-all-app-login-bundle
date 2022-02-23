<?php

declare(strict_types=1);

namespace vBridgeCloud\CallLoginBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

final class AuthController
{
    public function index(): Response
    {
        return new Response('You\'re not supposed to see this.');
    }
}
