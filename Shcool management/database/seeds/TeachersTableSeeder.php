<?php

use App\Classroom;
use App\Teacher;
use App\User;
use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = [
            [
                'name' => 'Amina Hassan',
                'gender' => 'Female',
                'experience' => '8 years',
                'phone' => '091000001',
                'email' => 'amina.hassan@school.test',
                'classroom' => 'Science A',
                'level_id' => 1,
            ],
            [
                'name' => 'Omar Saleh',
                'gender' => 'Male',
                'experience' => '6 years',
                'phone' => '091000002',
                'email' => 'omar.saleh@school.test',
                'classroom' => 'Science B',
                'level_id' => 1,
            ],
            [
                'name' => 'Layla Nasser',
                'gender' => 'Female',
                'experience' => '7 years',
                'phone' => '091000003',
                'email' => 'layla.nasser@school.test',
                'classroom' => 'Arts A',
                'level_id' => 2,
            ],
            [
                'name' => 'Yousef Adel',
                'gender' => 'Male',
                'experience' => '9 years',
                'phone' => '091000004',
                'email' => 'yousef.adel@school.test',
                'classroom' => 'Arts B',
                'level_id' => 2,
            ],
            [
                'name' => 'Sara Khaled',
                'gender' => 'Female',
                'experience' => '5 years',
                'phone' => '091000005',
                'email' => 'sara.khaled@school.test',
                'classroom' => 'Commerce A',
                'level_id' => 3,
            ],
            [
                'name' => 'Mahmoud Fathi',
                'gender' => 'Male',
                'experience' => '10 years',
                'phone' => '091000006',
                'email' => 'mahmoud.fathi@school.test',
                'classroom' => 'Commerce B',
                'level_id' => 3,
            ],
        ];

        $classrooms = Classroom::orderBy('id')->get()->keyBy('name');

        foreach ($teachers as $data) {
            $teacher = Teacher::create([
                'name' => $data['name'],
                'gender' => $data['gender'],
                'experience' => $data['experience'],
                'phone' => $data['phone'],
                'classroom_id' => (string) $classrooms[$data['classroom']]->id,
                'level_id' => (string) $data['level_id'],
            ]);

            User::create([
                'name' => $teacher->name,
                'email' => $data['email'],
                'password' => bcrypt('secret123'),
                'userable_id' => $teacher->id,
                'userable_type' => 'Teacher',
            ]);
        }
    }
}
