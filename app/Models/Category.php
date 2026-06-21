<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    /**
     * Relasi Many-to-One dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi One-to-Many dengan Menu
     */
    public function menus()
    {
        return $this->hasMany(Menu::class, 'kategori_id');
    }
}
