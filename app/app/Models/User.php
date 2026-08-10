<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nip_username',
        'name',
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
            'password' => 'hashed',
        ];
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by_user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'SUPERVISOR';
    }

    public function isOperator(): bool
    {
        return $this->role === 'OPERATOR';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'BENDAHARA';
    }

    public function canUpload(): bool
    {
        return in_array($this->role, ['ADMIN', 'SUPERVISOR', 'OPERATOR']);
    }

    public function canVerify(): bool
    {
        return in_array($this->role, ['ADMIN', 'BENDAHARA']);
    }

    public function canManageMaster(): bool
    {
        return in_array($this->role, ['ADMIN', 'SUPERVISOR']);
    }
}
