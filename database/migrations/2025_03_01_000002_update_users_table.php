<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('birth_date');
            }
            if (!Schema::hasColumn('users', 'rating')) {
                $table->integer('rating')->default(0)->after('phone');
            }
            if (!Schema::hasColumn('users', 'project_id')) {
                $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null')->after('rating');
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('project_id');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'group')) {
                $table->string('group')->nullable()->after('avatar');
            }
        });

        // Make name nullable
        if (Schema::hasColumn('users', 'name')) {
            DB::table('users')->whereNull('name')->update(['name' => '']);
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                DB::table('users')->whereNull('name')->update(['name' => '']);
                $table->string('name')->nullable(false)->change();
            }
            if (Schema::hasColumn('users', 'project_id')) {
                $table->dropForeign(['project_id']);
            }
            $columns = ['first_name', 'middle_name', 'last_name', 'birth_date', 'phone', 'rating', 'project_id', 'is_admin', 'avatar', 'group'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
