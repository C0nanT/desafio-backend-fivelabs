<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        User::factory()->count(100)->create();
        
        $users = [
            [
                'name' => 'conan1',
                'email' => 'conan1@5labs.com.br',
                'password' => Hash::make('123456'),
                'is_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'conan2',
                'email' => 'conan2@5labs.com.br',
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ],
            [
                'name' => 'conan3',
                'email' => 'conan3@5labs.com.br',
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'conan4',
                'email' => 'conan4@5labs.com.br',
                'password' => Hash::make('123456'),
                'is_admin' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'conan5',
                'email' => 'conan5@5labs.com.br',
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
                
        User::insert($users);
    }
}
