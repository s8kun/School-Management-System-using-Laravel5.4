<?php

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Level 1', 'Level 2', 'Level 3'] as $name) {
            Level::create(['name' => $name]);
        }
    }
}
