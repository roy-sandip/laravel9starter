<?php 
namespace App\PDF;
use Illuminate\Support\Facades\Storage;


class BillingStatement extends \RoySandip\LaravelTcpdf\PDF
{

    protected $print_header = true;
    protected $print_footer = true;


    public $header = true;
    public $footer = true;

    public $margins = [
	        'left'  => 0,
	        'top'   => 5,
	        'right' => 0,
    	];


    

    public function Header()
    {
        $path = 'admin/print/logo.jpg';
        $logo = Storage::get($path);
        $this->SetY(5);
        $this->view('admin.pdf.header', ['logo' => base64_encode($logo)]);
    }

    public function Footer()
    {
        $this->SetY(-10);
        $this->SetFontSize(9);
        $this->WriteHTML('Page '.$this->getPage().'/'.$this->getNumPages() . ' | Printed at '.date('h:ia d/m/Y'));
    }


}