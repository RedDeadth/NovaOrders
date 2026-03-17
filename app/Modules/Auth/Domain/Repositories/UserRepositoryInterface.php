<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Repositories;

use App\Modules\Auth\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user, string $hashedPassword): User;
    public function update(User $user): User;
    public function delete(int $id): void;
    public function all(): array;
}
