<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Application\DTOs\RegisterDTO;
use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\ValueObjects\UserRole;
use App\Shared\Domain\Exceptions\DomainException;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(RegisterDTO $dto): User
    {
        $existing = $this->userRepository->findByEmail($dto->email);

        if ($existing !== null) {
            throw DomainException::because("El email {$dto->email} ya está registrado");
        }

        $user = User::create(
            name: $dto->name,
            email: $dto->email,
            role: UserRole::from($dto->role),
        );

        return $this->userRepository->save($user, Hash::make($dto->password));
    }
}
