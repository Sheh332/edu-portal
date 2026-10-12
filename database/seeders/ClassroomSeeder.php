<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        // Popular School & College Class options (Pakistan SSC / HSSC / General & University Semesters)
        $classes = [
            ['name' => 'Class 9 (SSC-I)', 'code' => 'SSC-9', 'section' => 'Science / General'],
            ['name' => 'Class 10 (SSC-II - Matric)', 'code' => 'SSC-10', 'section' => 'Science / Computer'],
            ['name' => '1st Year (HSSC-I - FSc/ICS)', 'code' => 'HSSC-11', 'section' => 'Pre-Engineering / Medical / ICS'],
            ['name' => '2nd Year (HSSC-II - Inter)', 'code' => 'HSSC-12', 'section' => 'Pre-Engineering / Medical / ICS'],
            ['name' => 'Class 8', 'code' => 'CL-8', 'section' => 'Middle Wing'],
            ['name' => 'Class 7', 'code' => 'CL-7', 'section' => 'Middle Wing'],
            ['name' => 'BS Computer Science - Semester 1', 'code' => 'BSCS-S1', 'section' => 'Morning'],
            ['name' => 'BS Computer Science - Semester 2', 'code' => 'BSCS-S2', 'section' => 'Morning'],
        ];

        foreach ($classes as $c) {
            Classroom::firstOrCreate(['name' => $c['name']], $c);
        }

        // Ensure teacher role has necessary permissions
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $teacherRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Create sample Teacher account
        $teacherUser = User::updateOrCreate(
            ['email' => 'teacher@ams.test'],
            [
                'name' => 'Prof. Tariq Ahmed',
                'password' => Hash::make('password'),
                'phone' => '+923001234567',
            ]
        );
        $teacherUser->syncRoles(['teacher']);

        // Assign Class 9 and Class 10 to Teacher
        $class9 = Classroom::where('name', 'Class 9 (SSC-I)')->first();
        $class10 = Classroom::where('name', 'Class 10 (SSC-II - Matric)')->first();

        if ($class9 && $class10) {
            $teacherUser->taughtClasses()->syncWithoutDetaching([$class9->id, $class10->id]);
        }

        // Assign John Student to Class 9
        $student = User::where('email', 'student@ams.test')->first();
        if ($student && $class9) {
            $student->update(['classroom_id' => $class9->id]);
        }
    }
}
