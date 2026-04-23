<?php

use App\Admin;
use App\User;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'System Administrator',
            'username' => 'admin',
        ]);

        User::create([
            'name' => $admin->name,
            'email' => 'admin@school.test',
            'password' => bcrypt('secret123'),
            'userable_id' => $admin->id,
            'userable_type' => 'Admin',
        ]);
    }
}
