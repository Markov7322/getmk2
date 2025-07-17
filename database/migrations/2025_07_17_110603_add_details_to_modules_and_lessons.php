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
        Schema::table('modules', function (Blueprint $table) {
            $table->text('description')->nullable();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('pdf_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['description', 'video_url', 'pdf_path']);
        });
    }
};
