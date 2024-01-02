<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{
    User,
    Role
};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $userRole = Role::where('name', 'User')->first()->id;
        $adminRole = Role::where('name', 'Admin')->first()->id;

        User::create([
            'email' => 'user@user.com',
            'password' => bcrypt('password123'),
            'role_id' => $userRole
        ]);

        User::create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123'),
            'role_id' => $adminRole
        ]);
    }
}
