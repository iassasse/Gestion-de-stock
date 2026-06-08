<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'profile_picture',
        'is_super_chef_magasinier',
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
            'is_active' => 'boolean',
            'is_super_chef_magasinier' => 'boolean',
        ];
    }

    public function isChefMagasinier(): bool
    {
        return $this->role === 'Chef Magasinier';
    }

    public function getRoleLevel(): int
    {
        if ($this->is_super_chef_magasinier) {
            return 3;
        }
        if ($this->role === 'Chef Magasinier') {
            return 2;
        }
        return 1;
    }

    public function canManage(User $target): bool
    {
        if ($this->getRoleLevel() <= 1) {
            return false;
        }

        if ($this->id === $target->id) {
            return true;
        }

        return $this->getRoleLevel() > $target->getRoleLevel();
    }
}
