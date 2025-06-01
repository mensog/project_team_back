<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('journals', function (Blueprint $table) {
            if (Schema::hasColumn('journals', 'action')) {
                $table->dropColumn('action');
            }
            if (Schema::hasColumn('journals', 'status')) {
                $table->dropColumn('status');
            }
            if (!Schema::hasColumn('journals', 'title')) {
                $table->string('title')->after('user_id');
            }
            if (!Schema::hasColumn('journals', 'type')) {
                $table->enum('type', ['event', 'meeting'])->after('title');
            }
            if (Schema::hasColumn('journals', 'date')) {
                $table->date('date')->nullable(false)->change();
            }
        });
    }

    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            if (!Schema::hasColumn('journals', 'action')) {
                $table->text('action')->after('id');
            }
            if (!Schema::hasColumn('journals', 'status')) {
                $table->string('status')->default('present')->after('date');
            }
            if (Schema::hasColumn('journals', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('journals', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('journals', 'date')) {
                $table->date('date')->nullable()->change();
            }
        });
    }
};
