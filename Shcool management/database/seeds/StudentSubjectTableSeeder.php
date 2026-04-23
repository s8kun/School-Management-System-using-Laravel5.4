<?php

use App\Student;
use App\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = Student::all();
        $subjects = Subject::all()->groupBy('level_id');

        foreach ($students as $student) {
            if (! isset($subjects[$student->level_id])) {
                continue;
            }

            foreach ($subjects[$student->level_id] as $subject) {
                DB::table('student_subject')->insert([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                ]);
            }
        }
    }
}
