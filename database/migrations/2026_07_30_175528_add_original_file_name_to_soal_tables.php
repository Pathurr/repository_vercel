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
        Schema::table('soal_ujian', function (Blueprint $table) {
            $table->string('original_file_name')->nullable()->after('file_path');
        });
        Schema::table('soal_kuis', function (Blueprint $table) {
            $table->string('original_file_name')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_tables', function (Blueprint $table) {
            //
        });
    }
};
