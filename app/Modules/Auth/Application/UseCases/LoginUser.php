<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Application\DTOs\LoginDTO;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\DomainException;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(LoginDTO $dto): array
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw DomainException::because('Credenciales inválidas');
        }

        $authUser = Auth::user();
        $token = $authUser->createToken('api-token')->plainTextToken;

        $user = $this->userRepository->findByEmail($dto->email);

        return [
            'user' => $user->toArray(),
            'token' => $token,
        ];
    }
}
