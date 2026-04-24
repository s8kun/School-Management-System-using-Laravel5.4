<?php

use App\Models\Classroom;
use App\Models\Classgroup;
use Illuminate\Database\Seeder;

class ClassroomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classgroups = Classgroup::orderBy('id')->get()->keyBy('name');

        $classrooms = [
            ['name' => 'Science A', 'classgroup' => 'Science'],
            ['name' => 'Science B', 'classgroup' => 'Science'],
            ['name' => 'Arts A', 'classgroup' => 'Arts'],
            ['name' => 'Arts B', 'classgroup' => 'Arts'],
            ['name' => 'Commerce A', 'classgroup' => 'Commerce'],
            ['name' => 'Commerce B', 'classgroup' => 'Commerce'],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create([
                'name' => $classroom['name'],
                'classgroup_id' => $classgroups[$classroom['classgroup']]->id,
            ]);
        }
    }
}
