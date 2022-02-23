<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Auth;

use Doctrine\DBAL\Connection;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;

final class JwtConfigurationFactory
{
    public function __construct(
        private Connection $mysqlConnection
    ) {
    }

    public function createConfiguration(): Configuration
    {
        /** @var array{client_id: string, client_secret: string} $client */
        $client = $this->mysqlConnection
            ->fetchAssociative('SELECT client_id, client_secret FROM oauth_apps WHERE id = 1');

        $configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($client['client_secret'])
        );
        $configuration->setValidationConstraints(
            new IssuedBy($client['client_id']),
            new LooseValidAt(SystemClock::fromSystemTimezone())
        );

        return $configuration;
    }
}
