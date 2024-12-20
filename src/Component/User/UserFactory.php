<?php

declare(strict_types=1);

namespace App\Component\User;

use App\Entity\Roles;
use App\Entity\User;
use App\Repository\RolesRepository;
use DateTime;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class UserFactory
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private RolesRepository             $rolesRepository
    )
    {
    }

    public function create(
        ?string $email,
        ?array  $roles,
        ?string $password,
        bool $isRoleRoot = false
    ): User
    {
        $rol = $this->getValidatedRole($roles, $isRoleRoot);

        $user = new User();
        $this->setUserProperties($user, $email, $password, $rol);

        return $user;
    }

    private function getValidatedRole(?array $roles, bool $isRoleRoot): Roles
    {
        if (empty($roles)) {
            throw new InvalidArgumentException('Roles should not be empty.');
        }

        if ($this->rolesRepository->isRootUser($roles) && $isRoleRoot !== true) {
            throw new AccessDeniedException('Root user cannot be created.');
        }

        return $this->rolesRepository->roelByRoleObject($roles);
    }

    private function setUserProperties(User $user, ?string $email, ?string $password, Roles $rol): void
    {
        $user->setEmail($email);
        $user->setRoles([$rol->getName()]);
        $user->setCreatedAt(new DateTime());
        $user->addRol($rol);

        $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
    }

}
