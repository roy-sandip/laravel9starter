<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            AddressSeeder::class,
            AgentSeeder::class,
            CompanySeeder::class,
            ServiceSeeder::class,
            ShipmentSeeder::class,        
       ]);

        User::factory()->create([
            'name'      => 'Super User',
            'email'     => 'admin@email.com',
            'username'  => 'admin',
            'password'  => \Hash::make('password'),
            'agent_id'  => Agent::MAIN_BRANCH_ID,
            'is_super'  => true,
        ]);
        
    }
}
