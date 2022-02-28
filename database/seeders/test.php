<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class test extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'username' => 'abdo',
            'fname' => 'Abdelrahman',
            'lname' => 'Saied',
            'gender' => 'male',
            'password' => Hash::make('password')
        ]);
        DB::table('specializations')->insert([
            'name' => "Hart"
        ]);
        DB::table('doctors')->insert([
            'id' => 1,
            'description' => "hi",
            'img_name' => "1",
            'street' => "1",
            'city' => "1",
            'specialization_id' => 1,
            'fees' => 100
        ]);
        DB::table('appointments')->insert([
            'start_time' => "12:30:00",
            'date' => "2022:02:22",
            'patient_limit' => 10,
            'examination_time' => 30,
            'doctor_id' => 1
        ]);
        DB::table('users')->insert([
            'username' => 'ahmed',
            'fname' => 'Ahmed',
            'lname' => 'Mohamed',
            'gender' => 'male',
            'password' => Hash::make('password')
        ]);
        DB::table('patients')->insert([
            'id' => 2
            
        ]);
    }
}
