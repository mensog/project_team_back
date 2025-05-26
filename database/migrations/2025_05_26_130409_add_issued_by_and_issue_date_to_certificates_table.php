<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'issued_by')) {
                $table->string('issued_by')->after('file_path');
            }
            if (!Schema::hasColumn('certificates', 'issue_date')) {
                $table->date('issue_date')->nullable()->after('issued_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['issued_by', 'issue_date']);
        }); 
    }
};
