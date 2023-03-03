<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Validation\Rule;


use App\Models\Agent;
use App\Models\Billing;
use App\Models\Invoice;
use App\Models\InvoiceTransaction as Transaction;
use App\Models\Shipment;

class AgentBillingController extends Controller
{
    public function index()
    {
        
    }

    public function show($id)
    {
        
    }

    public function create(Request $request, $id)
    {
        

    }

    public function updateBill(Request $request, $agent, $id)
    {
   
    }


    public function generateInvoice(Request $request, $id)
    {
        
    }


}
