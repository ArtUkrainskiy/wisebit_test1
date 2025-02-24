<?php

namespace user\infrastructure\db\pgsql;

use user\domain\entity\User;
use user\domain\repository\UserRepositoryInterface;


class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        // TODO: Implement
        return null;
    }

    public function create(User $user): User
    {
        // TODO: Implement

        return $user;
    }

    public function update(User $user): User
    {
        // TODO: Implement

        return $user;
    }

    public function existsByName(string $name): bool
    {
        // TODO: Implement

        return false;
    }

    public function existsByEmail(string $email): bool
    {
        // TODO: Implement

        return false;
    }
}
