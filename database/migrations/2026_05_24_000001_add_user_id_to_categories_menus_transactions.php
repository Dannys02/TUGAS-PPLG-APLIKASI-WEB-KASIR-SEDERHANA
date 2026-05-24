<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah user_id ke categories
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->default(1)->constrained('users')->onDelete('cascade')->after('id');
        });

        // Tambah user_id ke menus
        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('user_id')->default(1)->constrained('users')->onDelete('cascade')->after('id');
        });

        // Tambah user_id ke transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->default(1)->constrained('users')->onDelete('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeignIdFor('users');
        });
    }
};
