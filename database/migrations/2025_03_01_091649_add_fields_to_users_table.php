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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->date('birth_date')->nullable()->after('email');
            $table->string('phone')->nullable()->after('birth_date');
            $table->integer('rating')->default(0)->after('phone');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null')->after('rating');
            $table->boolean('is_admin')->default(false)->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'project_id')) {
                $table->dropForeign(['project_id']);
            }
            $columns = ['first_name', 'middle_name', 'last_name', 'birth_date', 'phone', 'rating', 'project_id', 'is_admin'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
