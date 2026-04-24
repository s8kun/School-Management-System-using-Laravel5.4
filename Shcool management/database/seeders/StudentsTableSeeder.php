<?php

use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            [
                'name' => 'Ali Mohamed',
                'gender' => 'Male',
                'age' => 14,
                'address' => 'Tripoli - Street 1',
                'email' => 'ali.mohamed@school.test',
                'classroom' => 'Science A',
                'level_id' => 1,
            ],
            [
                'name' => 'Mariam Salem',
                'gender' => 'Female',
                'age' => 15,
                'address' => 'Tripoli - Street 2',
                'email' => 'mariam.salem@school.test',
                'classroom' => 'Science B',
                'level_id' => 1,
            ],
            [
                'name' => 'Khaled Faraj',
                'gender' => 'Male',
                'age' => 14,
                'address' => 'Tripoli - Street 3',
                'email' => 'khaled.faraj@school.test',
                'classroom' => 'Arts A',
                'level_id' => 2,
            ],
            [
                'name' => 'Nour Ali',
                'gender' => 'Female',
                'age' => 15,
                'address' => 'Tripoli - Street 4',
                'email' => 'nour.ali@school.test',
                'classroom' => 'Arts B',
                'level_id' => 2,
            ],
            [
                'name' => 'Hassan Omar',
                'gender' => 'Male',
                'age' => 16,
                'address' => 'Tripoli - Street 5',
                'email' => 'hassan.omar@school.test',
                'classroom' => 'Commerce A',
                'level_id' => 3,
            ],
            [
                'name' => 'Rana Adel',
                'gender' => 'Female',
                'age' => 16,
                'address' => 'Tripoli - Street 6',
                'email' => 'rana.adel@school.test',
                'classroom' => 'Commerce B',
                'level_id' => 3,
            ],
        ];

        $classrooms = Classroom::orderBy('id')->get()->keyBy('name');

        foreach ($students as $data) {
            $student = Student::create([
                'name' => $data['name'],
                'gender' => $data['gender'],
                'age' => $data['age'],
                'address' => $data['address'],
                'classroom_id' => $classrooms[$data['classroom']]->id,
                'level_id' => $data['level_id'],
            ]);

            User::create([
                'name' => $student->name,
                'email' => $data['email'],
                'password' => bcrypt('secret123'),
                'userable_id' => $student->id,
                'userable_type' => 'Student',
            ]);
        }
    }
}
