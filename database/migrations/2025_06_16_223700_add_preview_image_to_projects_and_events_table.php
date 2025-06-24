<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'preview_image')) {
                $table->string('preview_image')->nullable()->after('name');
            }
        });
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'preview_image')) {
                $table->string('preview_image')->nullable()->after('content');
            }
        });
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'preview_image')) {
                $table->string('preview_image')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'preview_image')) {
                $table->dropColumn('preview_image');
            }
        });
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'preview_image')) {
                $table->dropColumn('preview_image');
            }
        });
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'preview_image')) {
                $table->dropColumn('preview_image');
            }
        });
    }
};
