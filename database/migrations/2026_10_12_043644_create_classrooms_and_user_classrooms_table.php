<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. Class 9, Class 10, 1st Year (HSSC-I), Semester 1
            $table->string('code')->nullable(); // e.g. CL-09, SSC-1
            $table->string('section')->nullable(); // e.g. Section A, Pre-Medical, Morning
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Student's single enrolled class
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->after('profile_picture')->constrained('classrooms')->nullOnDelete();
        });

        // Teacher's assigned/taught classes (many-to-many: teacher can teach Class 9, 10, etc.)
        Schema::create('classroom_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'classroom_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_teacher');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });

        Schema::dropIfExists('classrooms');
    }
};
