<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Repositories;

use App\Models\User as UserModel;
use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = UserModel::where('email', $email)->first();
        return $model ? $this->toDomain($model) : null;
    }

    public function save(User $user, string $hashedPassword): User
    {
        $model = UserModel::create([
            'name' => $user->name(),
            'email' => $user->email()->value(),
            'password' => $hashedPassword,
            'role' => $user->role()->value,
        ]);

        return $this->toDomain($model);
    }

    public function update(User $user): User
    {
        $model = UserModel::findOrFail($user->id());
        $model->update([
            'name' => $user->name(),
            'email' => $user->email()->value(),
            'role' => $user->role()->value,
        ]);

        return $this->toDomain($model->fresh());
    }

    public function delete(int $id): void
    {
        UserModel::findOrFail($id)->delete();
    }

    public function all(): array
    {
        return UserModel::all()->map(fn($model) => $this->toDomain($model))->toArray();
    }

    private function toDomain(UserModel $model): User
    {
        return User::fromArray([
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'role' => $model->role->value,
            'created_at' => $model->created_at?->toISOString(),
        ]);
    }
}
