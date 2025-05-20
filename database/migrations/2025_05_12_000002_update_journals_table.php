<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'date')) {
                $table->date('date')->nullable()->after('action');
            }
            if (!Schema::hasColumn('journals', 'status')) {
                $table->string('status')->default('present')->after('date');
            }
            if (!Schema::hasColumn('journals', 'participant_id')) {
                $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            if (Schema::hasColumn('journals', 'date')) {
                $table->dropColumn('date');
            }
            if (Schema::hasColumn('journals', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('journals', 'participant_id')) {
                $table->dropForeign(['participant_id']);
                $table->dropColumn('participant_id');
            }
        });
    }
};
