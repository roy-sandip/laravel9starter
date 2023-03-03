<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         User::upsert([
                [
                    'name'  => 'Super User',
                    'email' => 'super@email.com',
                    'username' => 'super',
                    'agent_id' => 1,
                    'role'      => User::SUPER,
                    'password'  => \Hash::make('password'),
                ],
                [
                    'name'  => 'Admin User',
                    'email' => 'admin@email.com',
                    'username' => 'admin',
                    'agent_id' => 1,
                    'role'  => User::ADMIN,
                    'password' => \Hash::make('password'),
                ],
                [
                    'name'  => 'Agent User',
                    'email' => 'agent@email.com',
                    'username' => 'agent',
                    'agent_id' => 1,
                    'role'  => User::AGENT,
                    'password' => \Hash::make('password'),
                ],
                [
                    'name'  => 'General User',
                    'email' => 'user@email.com',
                    'username' => 'user',
                    'role'  => User::USER,
                    'agent_id' => 1,
                    'password' => \Hash::make('password'),
                ],
        ], ['username']);
        $agentList = Agent::select('id')->get()->pluck('id');
        $roles = User::roles(['super', 'admin']);
        User::factory()
            ->count(100)
            ->sequence(function($sequence)use($agentList, $roles){
                return [
                    'agent_id'  => $agentList->random(),
                    'role'      => $roles[array_rand($roles)],
                ];
            })
            ->create();
    }
}
