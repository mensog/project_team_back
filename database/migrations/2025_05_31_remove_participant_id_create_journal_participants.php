<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('journals', function (Blueprint $table) {
            if (Schema::hasColumn('journals', 'participant_id')) {
                $table->dropForeign(['participant_id']);
                $table->dropColumn('participant_id');
            }
        });

        Schema::create('journal_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_participants');

        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'participant_id')) {
                $table->foreignId('participant_id')->constrained('users')->onDelete('cascade')->after('status');
            }
        });
    }
};
