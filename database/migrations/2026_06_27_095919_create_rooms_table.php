<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->string('kode',20)->unique();
            $table->string('nama',150);
            $table->string('gedung',100);
            $table->tinyInteger('lantai');

            $table->smallInteger('kapasitas');

            $table->string('alamat')->nullable();

            $table->text('deskripsi')->nullable();

            $table->string('foto')->nullable();

            $table->time('jam_buka');

            $table->time('jam_tutup');

            $table->tinyInteger('max_durasi_jam')->default(4);

            $table->enum('status',[
                'Tersedia',
                'Maintenance'
            ])->default('Tersedia');

            $table->foreignId('faculty_id')
                ->nullable()
                ->constrained('faculties')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};