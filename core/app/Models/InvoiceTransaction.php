<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;


class InvoiceTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'type', 'datetime', 'comment'];
    const CREDIT = 'c';
    const DEBIT = 'd';

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function type($string)
    {
        $this->type = $this->getType($string);
        return $this;
    }

    public function isCredit()
    {
        return $this->type == self::CREDIT;
    }

    public function isDebit()
    {
        return $this->type == self::DEBIT;
    }

    public function getType($string)
    {
        switch ($string) {
            case 'debit':
                return self::DEBIT;break;
            
            default:
                return self::CREDIT;break;
        }
    }
}
