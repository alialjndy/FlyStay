<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'name'=>'Albrens1',
            'email'=>'alialjndy2@gmail.com',
            'phone_number'=>'0998680361',
            'password'=>bcrypt('strongPass123@')
        ]);
        $customerUser = User::create([
            'name'=>'Albrens2',
            'email'=>'alialjndy874@gmail.com',
            'phone_number'=>'0998680360',
            'password'=>bcrypt('strongPass123@'),
        ]);
        $flightAgentUser = User::create([
            'name'=>'flightAgent',
            'email'=>'flightAgent@gmail.com',
            'phone_number'=>'000000001',
            'password'=>bcrypt('strongPass123@'),
        ]);
        $flightAgentUser->assignRole('flight_agent');
        $adminUser->assignRole('admin');
        $customerUser->assignRole('customer');
    }
}
