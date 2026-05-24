<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Transaction extends Model
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
     * Relasi One-to-Many dengan DetailTransaction
     */
    public function details()
    {
        return $this->hasMany(DetailTransaction::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope untuk filter transaksi berdasarkan bulan dan tahun
     *
     * @param Builder $query
     * @param int $month (1-12)
     * @param int $year
     * @return Builder
     */
    public function scopeByMonthYear(Builder $query, int $month, int $year): Builder
    {
        return $query->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year);
    }

    /**
     * Scope untuk mendapatkan bulan dan tahun saat ini (default filter)
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeCurrentMonth(Builder $query): Builder
    {
        $now = Carbon::now();
        return $this->scopeByMonthYear($query, $now->month, $now->year);
    }
}
