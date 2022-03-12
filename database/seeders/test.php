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
            'password' => Hash::make('password'),
            'phone' => '01208347473'
        ]);
        DB::table('specializations')->insert([
            'name' => "Heart"
        ]);
        DB::table('doctors')->insert([
            'id' => 1,
            'title' => "professor",
            'description' => "hi",
            'img_name' => "1.png",
            'street' => "30",
            'city' => "Cairo",
            'specialization_id' => 1,
            'fees' => 100
        ]);
        DB::table('users')->insert([
            'username' => 'mahmod',
            'fname' => 'Maohmod',
            'lname' => 'Hassan',
            'gender' => 'male',
            'password' => Hash::make('password'),
            'phone' => '01208348484'
        ]);
        DB::table('doctors')->insert([
            'id' => 2,
            'title' => "lecturer",
            'description' => "hi",
            'img_name' => "2.png",
            'street' => "25",
            'city' => "Alexandria",
            'specialization_id' => 1,
            'fees' => 200
        ]);
        DB::table('appointments')->insert([
            'start_time' => "12:30:00",
            'date' => "2022:02:22",
            'patient_limit' => 10,
            'examination_time' => 30,
            'doctor_id' => 1
        ]);
        DB::table('appointments')->insert([
            'start_time' => "12:30:00",
            'date' => "2022:02:22",
            'patient_limit' => 10,
            'examination_time' => 30,
            'doctor_id' => 2
        ]);
        DB::table('users')->insert([
            'username' => 'ahmed',
            'fname' => 'Ahmed',
            'lname' => 'Mohamed',
            'gender' => 'male',
            'password' => Hash::make('password'),
            'phone' => '01201227473'
        ]);
        DB::table('patients')->insert([
            'id' => 3
            
        ]);
        DB::table('users')->insert([
            'username' => 'mona',
            'fname' => 'Mona',
            'lname' => 'Ahmed',
            'gender' => 'female',
            'password' => Hash::make('password'),
            'phone' => '01108347473'
        ]);
        DB::table('patients')->insert([
            'id' => 4
            
        ]);
        DB::table('feedback')->insert([
            'patient_id' => 3,
            'doctor_id' => 1,
            'rate' => 4,
            'message' => 'Good Doctor',
        ]);
        DB::table('feedback')->insert([
            'patient_id' => 4,
            'doctor_id' => 2,
            'rate' => 5,
            'message' => 'Nice',
        ]);
        DB::table('feedback')->insert([
            'patient_id' => 4,
            'doctor_id' => 1,
            'rate' => 2,
            'message' => 'Good Doctor',
        ]);
        DB::table('feedback')->insert([
            'patient_id' => 3,
            'doctor_id' => 2,
            'rate' => 3,
            'message' => 'Nice',
        ]);
    }
}
