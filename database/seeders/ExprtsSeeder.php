<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExprtsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        // User 1
        $user = User::create([
            'name' => 'Yaseen',
            'email' => 'yaseen99@gmail.com',
            'password' => bcrypt('12312399@'), // Password will be hashed automatically due to the 'hashed' cast in User model
            'type' =>'expert' // Set the user type
           
        ]);
        $user->expert()->create(
            [
                'bio' => 'test bio',
                'industry' => 'network',
                'session_price' => 80
            ]
        );

         // User 2
         $user = User::create([
            'name' => 'Ahmed',
            'email' => 'ahmed@gmail.com',
            'password' => bcrypt('12312399@'), // Password will be hashed automatically due to the 'hashed' cast in User model
            'type' =>'expert' // Set the user type
           
        ]);
        $user->expert()->create(
            [
                'bio' => 'test bio2 ',
                'industry' => 'developer',
                'session_price' => 150
            ]
        );
        // 

         // User 1
         $user = User::create([
            'name' => 'salih',
            'email' => 'salih@gmail.com',
            'password' => bcrypt('12312399@'), // Password will be hashed automatically due to the 'hashed' cast in User model
            'type' =>'expert' // Set the user type
           
        ]);
        $user->expert()->create(
            [
                'bio' => 'test bio salih',
                'industry' => 'developer',
                'session_price' => 140
            ]
        );
    }
}
