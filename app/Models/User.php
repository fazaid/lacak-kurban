<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'role', 'password', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role checks ───────────────────────────────────────────────────

    public function isAdmin(): bool  { return $this->role === 'admin';  }
    public function isStaff(): bool  { return $this->role === 'staff';  }
    public function isViewer(): bool { return $this->role === 'viewer'; }

    // ── Permission shortcuts used in views and controllers ────────────

    /** Create, edit, update progress, upload photos, generate certificate */
    public function canWrite(): bool { return in_array($this->role, ['admin', 'staff']); }

    /** Delete sacrifices and photos */
    public function canDelete(): bool { return in_array($this->role, ['admin', 'staff']); }

    /** Export CSV */
    public function canExport(): bool { return in_array($this->role, ['admin', 'staff']); }

    /** Friendly label for UI display */
    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin'  => 'Admin',
            'staff'  => 'Staff',
            'viewer' => 'Viewer',
            default  => ucfirst($this->role ?? ''),
        };
    }

    /** Tailwind classes for the role badge */
    public function roleBadgeClass(): string
    {
        return match ($this->role) {
            'admin'  => 'bg-[#1D9E75]/15 text-[#1D9E75]',
            'staff'  => 'bg-blue-100 text-blue-700',
            'viewer' => 'bg-gray-100 text-gray-600',
            default  => 'bg-gray-100 text-gray-600',
        };
    }
}
