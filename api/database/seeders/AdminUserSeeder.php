<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('role_user')->truncate();
        Schema::enableForeignKeyConstraints();

        $admin = User::create([
            'name' => 'adminUser',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password')
        ]);
        $admin->roles()->attach(3);
    }
}
