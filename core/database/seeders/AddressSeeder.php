<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Address;


class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::factory()
                ->count(500)
                ->state(new Sequence(
                    ['type'  => Address::ADDRESS_RECEIVER],
                    ['type'  => Address::ADDRESS_SHIPPER, 'country' => 'BANGLADESH']
                ))
                ->create();
    }
}
