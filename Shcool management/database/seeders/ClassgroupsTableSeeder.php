<?php

use App\Models\Classgroup;
use Illuminate\Database\Seeder;

class ClassgroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Science', 'Arts', 'Commerce'] as $name) {
            Classgroup::create(['name' => $name]);
        }
    }
}
