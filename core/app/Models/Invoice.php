<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceTransaction;
use App\Models\Billing;
use App\Models\Agent;
use DB;

class Invoice extends Model
{
    use HasFactory;
    protected $casts = [
        'readonly'  => 'boolean',
        'from'      => 'datetime',
        'to'      => 'datetime',
    ];


    public function transactions()
    {
        return $this->hasMany(InvoiceTransaction::class);
    }
    
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function lastInvoice()
    {
        return $this->belongsTo(Invoice::class, 'parent_invoice')->withDefault();
    }

    public function calculateInvoice()
    {
            $this->balance = $this->total_bill - $this->total_paid;
            $this->total_due = ($this->balance > 0) ? $this->balance : 0;
            return $this;
    }

    public function reCalculate()
    {
        //get total of billings
        $billings = $this->billings()->select(DB::raw('SUM(bill) as bill, SUM(paid) as paid'))->first();

        //get transactions
        $transactions = $this->transactions;

        $this->total_bill = $billings->bill + $transactions->where('type', InvoiceTransaction::CREDIT)->sum('amount');
        $this->total_paid = $billings->paid + $transactions->where('type', InvoiceTransaction::DEBIT)->sum('amount');
        return $this->calculateInvoice();
    }

    public function deleteTransaction(InvoiceTransaction $transaction)
    {
        if($transaction->isCredit())
        {
            $this->total_bill = $this->total_bill - $transaction->amount;
        }elseif($transaction->isDebit())
        {
            $this->total_paid = $this->total_paid - $transaction->amount;
        }
        $transaction->delete();
        return $this->calculateInvoice();
    }

    public function updateTransaction(InvoiceTransaction $transaction)
    {
        
    }


    public function getBill()
    {
        return $this->total_bill;
    }

    public function getPaid()
    {
        return $this->total_paid;
    }

    public function getDue()
    {
        return $this->total_due;
    }
    public function getBalance()
    {
        return  number_format($this->balance, 2);
    }
    
    public function locked()
    {
        return $this->readonly;
    }

    public function lock()
    {
        $this->readonly = true;return $this;
    }
    public function unlock()
    {
        $this->readonly = false;return $this;
    }

    public function period($format = 'd M, Y')
    {
        return $this->from->format($format). '-'. $this->to->format($format);
    }



}
