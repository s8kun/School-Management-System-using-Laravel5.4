<?php

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['name' => 'Mathematics', 'score' => 100, 'level_id' => 1, 'teacher_id' => 1],
            ['name' => 'Physics', 'score' => 100, 'level_id' => 1, 'teacher_id' => 2],
            ['name' => 'Literature', 'score' => 100, 'level_id' => 2, 'teacher_id' => 3],
            ['name' => 'History', 'score' => 100, 'level_id' => 2, 'teacher_id' => 4],
            ['name' => 'Accounting', 'score' => 100, 'level_id' => 3, 'teacher_id' => 5],
            ['name' => 'Economics', 'score' => 100, 'level_id' => 3, 'teacher_id' => 6],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
