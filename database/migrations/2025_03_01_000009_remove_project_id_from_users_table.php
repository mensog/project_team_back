<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
        });
    }
};
