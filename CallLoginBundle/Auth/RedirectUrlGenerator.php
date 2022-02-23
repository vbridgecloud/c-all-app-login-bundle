<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Auth;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RedirectUrlGenerator
{
    public function __construct(
        private readonly string $authorizePath,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function generate(): string
    {
        return $this->urlGenerator->generate($this->authorizePath, [], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
