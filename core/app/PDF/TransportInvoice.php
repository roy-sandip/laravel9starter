<?php 
namespace App\PDF;
use Illuminate\Support\Facades\Storage;

class TransportInvoice extends \RoySandip\LaravelTcpdf\PDF
{

    protected $print_header = true;
    protected $print_footer = false;


    

    public $margins = [
	        'left'  => 0,
	        'top'   => 5,
	        'right' => 0,
    	];


    

    public function Header()
    {
        $this->SetY(145);
        $this->WriteHTML('<hr style="color:#ddd;" />');
    }


}