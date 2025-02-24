<?php

namespace user\domain\repository;

use user\domain\entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function create(User $user): User;

    public function update(User $user): User;

    public function existsByName(string $name): bool;

    public function existsByEmail(string $email): bool;
}