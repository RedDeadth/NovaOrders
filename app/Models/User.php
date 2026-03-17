<?php

namespace App\Models;

use App\Modules\Auth\Domain\ValueObjects\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function customer(): HasOne
    {
        return $this->hasOne(\App\Modules\Customer\Infrastructure\Models\CustomerModel::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(\App\Modules\Order\Infrastructure\Models\OrderModel::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isVendedor(): bool
    {
        return $this->role === UserRole::VENDEDOR;
    }

    public function canManageProducts(): bool
    {
        return in_array($this->role, [UserRole::ADMIN, UserRole::VENDEDOR]);
    }
}
