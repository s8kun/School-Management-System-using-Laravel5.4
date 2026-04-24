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
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('classroom_id')->change();
            $table->unsignedInteger('level_id')->change();

            $table->index('classroom_id', 'students_classroom_id_index');
            $table->index('level_id', 'students_level_id_index');
            $table->foreign('classroom_id', 'students_classroom_id_foreign')->references('id')->on('classrooms');
            $table->foreign('level_id', 'students_level_id_foreign')->references('id')->on('levels');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->index('classroom_id', 'teachers_classroom_id_index');
            $table->index('level_id', 'teachers_level_id_index');
            $table->foreign('classroom_id', 'teachers_classroom_id_foreign')->references('id')->on('classrooms');
            $table->foreign('level_id', 'teachers_level_id_foreign')->references('id')->on('levels');
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->unsignedInteger('classgroup_id')->change();

            $table->index('classgroup_id', 'classrooms_classgroup_id_index');
            $table->foreign('classgroup_id', 'classrooms_classgroup_id_foreign')->references('id')->on('classgroups');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedInteger('level_id')->change();
            $table->unsignedInteger('teacher_id')->change();

            $table->index('level_id', 'subjects_level_id_index');
            $table->index('teacher_id', 'subjects_teacher_id_index');
            $table->foreign('level_id', 'subjects_level_id_foreign')->references('id')->on('levels');
            $table->foreign('teacher_id', 'subjects_teacher_id_foreign')->references('id')->on('teachers');
        });

        Schema::table('student_subject', function (Blueprint $table) {
            $table->unsignedInteger('student_id')->change();
            $table->unsignedInteger('subject_id')->change();

            $table->index('subject_id', 'student_subject_subject_id_index');
            $table->foreign('student_id', 'student_subject_student_id_foreign')->references('id')->on('students');
            $table->foreign('subject_id', 'student_subject_subject_id_foreign')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_subject', function (Blueprint $table) {
            $table->dropForeign('student_subject_student_id_foreign');
            $table->dropForeign('student_subject_subject_id_foreign');
            $table->dropIndex('student_subject_subject_id_index');

            $table->integer('student_id')->change();
            $table->integer('subject_id')->change();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign('subjects_level_id_foreign');
            $table->dropForeign('subjects_teacher_id_foreign');
            $table->dropIndex('subjects_level_id_index');
            $table->dropIndex('subjects_teacher_id_index');

            $table->integer('level_id')->change();
            $table->integer('teacher_id')->change();
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign('classrooms_classgroup_id_foreign');
            $table->dropIndex('classrooms_classgroup_id_index');

            $table->integer('classgroup_id')->change();
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign('teachers_classroom_id_foreign');
            $table->dropForeign('teachers_level_id_foreign');
            $table->dropIndex('teachers_classroom_id_index');
            $table->dropIndex('teachers_level_id_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('students_classroom_id_foreign');
            $table->dropForeign('students_level_id_foreign');
            $table->dropIndex('students_classroom_id_index');
            $table->dropIndex('students_level_id_index');

            $table->integer('classroom_id')->change();
            $table->integer('level_id')->change();
        });
    }
};
