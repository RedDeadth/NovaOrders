<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\DTOs\LoginDTO;
use App\Modules\Auth\Application\DTOs\RegisterDTO;
use App\Modules\Auth\Application\UseCases\LoginUser;
use App\Modules\Auth\Application\UseCases\RegisterUser;
use App\Shared\Domain\Exceptions\DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function __construct(
        private readonly RegisterUser $registerUser,
        private readonly LoginUser $loginUser,
    ) {}

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,vendedor,cliente',
        ]);

        try {
            $dto = RegisterDTO::fromArray($request->all());
            $user = $this->registerUser->execute($dto);

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => $user->toArray(),
            ], 201);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $dto = LoginDTO::fromArray($request->all());
            $result = $this->loginUser->execute($dto);

            return response()->json([
                'message' => 'Login exitoso',
                'user' => $result['user'],
                'token' => $result['token'],
            ]);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role->value,
        ]);
    }
}
