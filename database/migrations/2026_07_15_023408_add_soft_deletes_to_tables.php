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
        // Menambahkan kolom deleted_at ke tabel kategoris
        Schema::table('kategoris', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Menambahkan kolom deleted_at ke tabel events
        Schema::table('events', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Menambahkan kolom deleted_at ke tabel tikets
        Schema::table('tikets', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Menambahkan kolom deleted_at ke tabel orders
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        // Menambahkan kolom deleted_at ke tabel detail_orders
        Schema::table('detail_orders', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tikets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('detail_orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
