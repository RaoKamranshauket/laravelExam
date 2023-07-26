<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create an admin users by default 
        User::updateOrCreate(['email' => 'admin@exam.com'],[
            'first_name' => 'Mubeen',
            'last_name' => 'ali',
            'email' => 'admin@exam.com',
            'password' => Hash::make('11221122'),
            'type' => config('constants.USER_TYPE.ADMIN')
        ]);
    }
}
