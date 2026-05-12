<?php

namespace App\Models;

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
        'photo',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ── Role helpers ──
    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isKasir(): bool  { return $this->role === 'kasir'; }
    public function isUser(): bool   { return $this->role === 'user'; }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    // ── Avatar: inisial dari nama ──
    public function getInitials(): string
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    // ── Warna background inisial (konsisten per user) ──
    public function getAvatarColor(): string
    {
        $colors = [
            '#1a237e', '#283593', '#1565c0', '#0277bd',
            '#00695c', '#2e7d32', '#e53935', '#c62828',
            '#6a1b9a', '#4527a0', '#d84315', '#37474f',
        ];
        return $colors[crc32($this->name) % count($colors)];
    }
}
