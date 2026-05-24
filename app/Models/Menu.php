<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    /**
     * Relasi Many-to-One dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-One dengan Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }
}
