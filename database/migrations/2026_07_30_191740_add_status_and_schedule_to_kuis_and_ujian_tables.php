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
        Schema::table('kuis', function (Blueprint $table) {
            $table->string('status')->default('published')->after('durasi_menit');
            $table->dateTime('mulai_at')->nullable()->after('status');
            $table->dateTime('selesai_at')->nullable()->after('mulai_at');
        });

        Schema::table('ujian', function (Blueprint $table) {
            $table->string('status')->default('published')->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuis', function (Blueprint $table) {
            $table->dropColumn(['status', 'mulai_at', 'selesai_at']);
        });

        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
