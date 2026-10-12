<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email'); // 'student', 'teacher', 'admin'
        });

        // Sync existing roles into column
        $adminRole = \Illuminate\Support\Facades\DB::table('roles')->where('name', 'admin')->first();
        $teacherRole = \Illuminate\Support\Facades\DB::table('roles')->where('name', 'teacher')->first();

        if ($adminRole) {
            $adminIds = \Illuminate\Support\Facades\DB::table('model_has_roles')->where('role_id', $adminRole->id)->pluck('model_id');
            \Illuminate\Support\Facades\DB::table('users')->whereIn('id', $adminIds)->update(['role' => 'admin']);
        }

        if ($teacherRole) {
            $teacherIds = \Illuminate\Support\Facades\DB::table('model_has_roles')->where('role_id', $teacherRole->id)->pluck('model_id');
            \Illuminate\Support\Facades\DB::table('users')->whereIn('id', $teacherIds)->update(['role' => 'teacher']);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
