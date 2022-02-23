<?php

declare(strict_types=1);

namespace App\vBridgeCloud\CallLoginBundle\Auth;

use Symfony\Component\Security\Core\User\UserInterface;

use function strpos;
use function strtoupper;

final class AuthenticatedUser implements UserInterface
{
    /** @var string[] */
    private array $roles = [];

    /**
     * @param string[] $roles
     */
    public function __construct(
        private string $id,
        private string $companyId,
        private string $name,
        private string $email,
        array $roles,
    ) {
        foreach ($roles as $role) {
            $this->roles[] = strtoupper(strpos($role, 'ROLE_') === false ? 'ROLE_' . $role : $role);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
