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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->text('catatan')->nullable();
            $table->datetime('dikumpulkan_at')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['belum', 'terlambat', 'tepat_waktu'])->default('belum');
            $table->unique(['tugas_id', 'siswa_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
