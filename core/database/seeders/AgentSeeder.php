<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agent;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Agent::updateOrCreate([
            'id'        => Agent::MAIN_BRANCH_ID,
            'name'      => 'Counter',
            'company'   => 'Counter',
        ], ['id' => Agent::MAIN_BRANCH_ID]);
        Agent::factory(15)->create();
    }
}
