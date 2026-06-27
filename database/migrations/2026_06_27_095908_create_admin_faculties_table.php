<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_faculties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('faculty_id')
                ->constrained('faculties')
                ->cascadeOnDelete();

            $table->string('position')->nullable();
            // contoh: validator, admin fakultas, kepala bagian

            $table->string('status')->default('active');
            // active, inactive

            $table->timestamps();

            $table->unique(['user_id', 'faculty_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_faculties');
    }
};
