<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();

        foreach ([
            'student_subject',
            'users',
            'subjects',
            'students',
            'teachers',
            'classrooms',
            'classgroups',
            'levels',
            'admins',
        ] as $table) {
            DB::table($table)->delete();
        }

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::table('sqlite_sequence')
                ->whereIn('name', [
                    'users',
                    'subjects',
                    'students',
                    'teachers',
                    'classrooms',
                    'classgroups',
                    'levels',
                    'admins',
                ])
                ->delete();
        }

        Schema::enableForeignKeyConstraints();

        $this->call(AdminsTableSeeder::class);
        $this->call(LevelsTableSeeder::class);
        $this->call(ClassgroupsTableSeeder::class);
        $this->call(ClassroomsTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(StudentSubjectTableSeeder::class);

        Model::reguard();
    }
}
