<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'phone', 'address', 'password', 'logo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi One-to-Many dengan Category
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relasi One-to-Many dengan Menu
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Relasi One-to-Many dengan Transaction
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
