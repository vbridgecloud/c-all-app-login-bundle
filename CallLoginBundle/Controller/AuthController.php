<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AuthController
{
    #[Route('/authorize')]
    public function index(): Response
    {
        return new Response('You\'re not supposed to see this.');
    }
}
