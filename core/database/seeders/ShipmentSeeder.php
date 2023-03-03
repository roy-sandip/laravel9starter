<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shipment;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addressList = \App\Models\Address::query()->select(['id', 'type'])->get()->groupBy('type');
        $agentList  = \App\Models\Agent::select('id')->get();
        $services  = \App\Models\Service::select('id')->get();
        $shipmentSerial = (Shipment::withOutGlobalScopes()->latest('id')->first()->id ?? 0) + Shipment::AWB_SERIAL;
        Shipment::factory()
                ->count(500)
                ->sequence(function($sequence)use($addressList, $agentList, $services, $shipmentSerial){
                    $update = (new Shipment)->addUpdate('Record Created', 'Sylhet-BD', now())->updates;
                    return [
                            'awb'       => ($shipmentSerial + $sequence->index),
                            'updates'   => $update,
                            'shipper_id'    => $addressList->get('shipper')->random()->id,
                            'receiver_id'    => $addressList->get('receiver')->random()->id,
                            'agent_id'      => $agentList->random()->id,
                            'service_id'      => $services->random()->id,
                    ];
                })->hasBilling(1)
                ->create();

    }
}
