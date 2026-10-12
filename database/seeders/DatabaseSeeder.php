<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ---- Permissions ------------------------------------------------
        $permissions = [
            'mark attendance',
            'view attendance',
            'request leave',
            'manage students',
            'manage attendance',
            'approve leave',
            'view reports',
            'manage grades',
            'assign tasks',
            'submit tasks',
            'approve tasks',
            'manage roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ---- Roles ------------------------------------------------------
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        $student = Role::firstOrCreate(['name' => 'student']);
        $student->syncPermissions([
            'mark attendance', 'view attendance', 'request leave', 'submit tasks',
        ]);

        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $teacher->syncPermissions([
            'view attendance', 'view reports', 'assign tasks', 'approve tasks',
        ]);

        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->syncPermissions([
            'manage students', 'approve leave', 'view reports',
        ]);

        // ---- Admin user -------------------------------------------------
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@ams.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'phone' => '+10000000000',
            ]
        );
        $adminUser->syncRoles(['admin']);

        // ---- Sample student --------------------------------------------
        $sample = User::updateOrCreate(
            ['email' => 'student@ams.test'],
            [
                'name' => 'John Student',
                'password' => Hash::make('password'),
                'phone' => '+10000000001',
            ]
        );
        $sample->syncRoles(['student']);

        // ---- Grade levels ----------------------------------------------
        $grades = [
            ['grade' => 'A', 'min_days' => 26, 'max_days' => 31, 'description' => '26+ days present'],
            ['grade' => 'B', 'min_days' => 20, 'max_days' => 25, 'description' => '20-25 days present'],
            ['grade' => 'C', 'min_days' => 15, 'max_days' => 19, 'description' => '15-19 days present'],
            ['grade' => 'D', 'min_days' => 10, 'max_days' => 14, 'description' => '10-14 days present'],
            ['grade' => 'F', 'min_days' => 0, 'max_days' => 9, 'description' => 'Below 10 days present'],
        ];

        foreach ($grades as $g) {
            GradeLevel::updateOrCreate(['grade' => $g['grade']], $g);
        }
    }
}
