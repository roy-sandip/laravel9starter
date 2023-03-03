<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Sequence;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                
        $companies = Company::all();
        if(count($companies) < 1)
        {
            $this->call([\Database\Seeders\CompanySeeder::class]);
            $companies = Company::all();
        }

        Service::factory()
                ->count($companies->count())
                ->state(new Sequence(function($sequence)use($companies){
                    $company = $companies->shift();
                    return [
                        'name'          => $company->name,
                        'public_name'   => $company->name,
                        'company_key'   => $company->key,
                    ];
                }))
                ->create();
    }
}
