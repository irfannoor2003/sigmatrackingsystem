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
            'name' => 'Hurrera Malik2',
            'email' => 'hurrera@sigmagroup.com.pk2',
            'password' => Hash::make('Hurrera@Admin$2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Umar Arshad2',
            'email' => 'sales@sigmagroup.com.pk2',
            'password' => Hash::make('Umar%Admin^2026'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Awais Anwar2',
            'email' => 'awais@sigmagroup.com.pk2',
            'password' => Hash::make('Awais!Sales#2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Sajjad Ali2',
            'email' => 'sajjad@sigmagroup.com.pk2',
            'password' => Hash::make('Sajjad@Sales$2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Irfan Ashraf2',
            'email' => 'irfan@sigmagroup.com.pk2',
             'password' => Hash::make('Irfan%Sales^2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Muhammad Ahmed2',
            'email' => 'ahmad@sigmagroup.com.pk2',
             'password' => Hash::make('Ahmed&Sales*2025'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Muhammad Farhan Malik2',
            'email' => 'farhanmalik1176yt@gmail.com2',
 'password' => Hash::make('Farhan(Sales)2025'),
            'role' => 'salesman'
        ]);
         User::create([
            'name' => 'Moeen Khalid2 ',
            'email' => 'moeenkhalid92@gmail.com2',
             'password' => Hash::make('Moeen_Sales+2025'),
            'role' => 'salesman'
        ]);User::create([
            'name' => 'Umar Arshad 22 ',
            'email' => 'coolcapri07@gmail.com2',
             'password' => Hash::make('Umar!Sales@2026'),
            'role' => 'salesman'
        ]);User::create([
            'name' => 'Hurrera Malik 22  ',
            'email' => 'hurreramalik11@gmail.com2',
             'password' => Hash::make('Hurrera#Sales%2026'),
            'role' => 'salesman'
        ]);
        User::create([
            'name' => 'Irfan Noor2 ',
            'email' => 'techby79@gmail.com2',
             'password' => Hash::make('Irfan!It@2025'),
            'role' => 'it'
        ]);
        User::create([
            'name' => 'Muhammad Mubashir2 ',
            'email' => 'sigmamubashir@gmail.com2',
             'password' => Hash::make('Mubashir%It^2025'),
            'role' => 'it'
        ]);
         User::create([
            'name' => 'Hunzla Malik 32  ',
            'email' => 'muhammadhunzla3@gmail.com2',
             'password' => Hash::make('Hunzla#It$2025'),
            'role' => 'it'
        ]);
         User::create([
            'name' => 'Moazam Shahid2   ',
            'email' => 'Moazam@sigmagroup.com.pk2',
             'password' => Hash::make('Moazam!Ac@2025'),
            'role' => 'account'
        ]);
        User::create([
            'name' => 'Ariba Fiaz2  ',
            'email' => 'Ariba@sigmagroup.com.pk2',
             'password' => Hash::make('Miss#Ac$2025'),
            'role' => 'account'
        ]);
        User::create([
            'name' => 'Arif Hussain2   ',
            'email' => 'arifhussain19810@gmail.com2',
             'password' => Hash::make('Arif!St@2025'),
            'role' => 'store'
        ]);
        User::create([
            'name' => 'Musharaf Naeem2  ',
            'email' => 'musharafnaeem997@gmail.com2',
             'password' => Hash::make('Musharaf#St$2025'),
            'role' => 'store'
        ]);
        User::create([
            'name' => 'Haseeb Chand2 ',
            'email' => 'hc9533678@gmail.com2',
             'password' => Hash::make('Haseeb!Ob@2025'),
            'role' => 'office_boy'
        ]);
    }
}
