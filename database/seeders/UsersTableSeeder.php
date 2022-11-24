<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        User::firstOrCreate([
            'email' => 'sr@mgk.dev',
        ], [
            'name'     => 'YvonEnbaver',
            'password' => \Hash::make('1234'),
        ])->assignRole('admin');
    }
}
