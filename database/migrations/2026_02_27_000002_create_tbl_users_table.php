<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('email');
            $table->string('password');
            $table->foreignId('role')->constrained('tbl_roles')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
