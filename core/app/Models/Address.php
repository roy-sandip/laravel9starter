<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Address extends Model
{
    use HasFactory;
    const ADDRESS_SHIPPER = 'shipper';
    const ADDRESS_RECEIVER = 'receiver';
    const ADDRESS_CONTACT = 'contact';


    public function getAddress()
    {
        $string  = nl2br($this->street) .'<br>';
        $string .= $this->city .' '. $this->zip .'<br>';
        $string .= '<b>' . $this->country . '</b>';
        
        return $string;
    }

   
    
    public function printStreetLines($ln = 4)
    {
        $street = $this->street ;
               
        while(substr_count($street, "\n") < $ln)
        {
            $street .= "\n";
        }
        
        return nl2br($street);
    }

    public static function types($except = [])
    {
        return array_diff([
            self::ADDRESS_SHIPPER,
            self::ADDRESS_RECEIVER,
            self::ADDRESS_CONTACT
        ], $except);
    }
}
