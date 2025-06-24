<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::table('journals', function (Blueprint $table) {
            if (Schema::hasColumn('journals', 'participant_id')) {
                $table->dropForeign(['participant_id']);
                $table->dropColumn('participant_id');
            }
        });

        Schema::create('journal_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('journal_participants');
        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'participant_id')) {
                $table->foreignId('participant_id')->nullable()->constrained('users')->onDelete('cascade');
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
