<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'Hunzla Malik',
            'email' => 'digitalsales@sigmagroup.com.pk',
            'password' => Hash::make('Hunzla!Admin#2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Hurrera Malik',
            'email' => 'hurrera@sigmagroup.com.pk',
            'password' => Hash::make('Hurrera@Admin$2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Umar Arshad',
            'email' => 'sales@sigmagroup.com.pk',
            'password' => Hash::make('Umar%Admin^2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Awais Anwar',
            'email' => 'awais@sigmagroup.com.pk',
            'password' => Hash::make('Awais!Sales#2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Sajjad Ali',
            'email' => 'sajjad@sigmagroup.com.pk',
            'password' => Hash::make('Sajjad@Sales$2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Irfan Ashraf',
            'email' => 'irfan@sigmagroup.com.pk',
             'password' => Hash::make('Irfan%Sales^2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Muhammad Ahmed',
            'email' => 'ahmad@sigmagroup.com.pk',
             'password' => Hash::make('Ahmed&Sales*2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Muhammad Farhan Malik',
            'email' => 'farhanmalik1176yt@gmail.com',
            'password' => Hash::make('Farhan(Sales)2025'),
            'role' => 'salesman'
        ]);
         User::create([
            'name' => 'Moeen Khalid ',
            'email' => 'moeenkhalid92@gmail.com',
             'password' => Hash::make('Moeen_Sales+2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Umar Arshad 2 ',
            'email' => 'coolcapri07@gmail.com',
             'password' => Hash::make('Umar!Sales@2026'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Hurrera Malik 2 ',
            'email' => 'hurreramalik11@gmail.com',
             'password' => Hash::make('Hurrera#Sales%2026'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Irfan Noor ',
            'email' => 'techby79@gmail.com',
             'password' => Hash::make('Irfan!It@2025'),
            'role' => 'it'
        ]);
        User::create([
            'name' => 'Muhammad Mubashir ',
            'email' => 'sigmamubashir@gmail.com',
             'password' => Hash::make('Mubashir%It^2025'),
            'role' => 'it'
        ]);
         User::create([
            'name' => 'Hunzla Malik 3  ',
            'email' => 'muhammadhunzla3@gmail.com',
             'password' => Hash::make('Hunzla#It$2025'),
            'role' => 'it'
        ]);
         User::create([
            'name' => 'Moazam Shahid  ',
            'email' => 'Moazam@sigmagroup.com.pk',
             'password' => Hash::make('Moazam!Ac@2025'),
            'role' => 'account'
        ]);
        User::create([
            'name' => 'Ariba Fiaz  ',
            'email' => 'Ariba@sigmagroup.com.pk',
             'password' => Hash::make('Miss#Ac$2025'),
            'role' => 'account'
        ]);
        User::create([
            'name' => 'Arif Hussain  ',
            'email' => 'arifhussain19810@gmail.com',
             'password' => Hash::make('Arif!St@2025'),
            'role' => 'store'
        ]);
        User::create([
            'name' => 'Musharaf Naeem  ',
            'email' => 'musharafnaeem997@gmail.com',
             'password' => Hash::make('Musharaf#St$2025'),
            'role' => 'store'
        ]);
        User::create([
            'name' => 'Haseeb Chand ',
            'email' => 'hc9533678@gmail.com',
             'password' => Hash::make('Haseeb!Ob@2025'),
            'role' => 'office_boy'
        ]);

    }
}
