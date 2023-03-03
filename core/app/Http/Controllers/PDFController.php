<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PDF\BillingStatement;
use App\PDF\TransportInvoice;
use App\Models\Invoice;
use App\Models\DHLBill;
use App\Models\Shipment;


class PDFController extends Controller
{
    public function printBillingInvoice($id)
    {
        $invoice = Invoice::query()
                            ->with([
                                    'transactions', 
                                    'billings', 
                                    'lastInvoice',
                                    'billings.shipment', 
                                    'billings.shipment.receiver', 
                                    'billings.shipment.service', 
                                    'agent',
                                ])
                            ->findOrFail($id);
        $pdf = new BillingStatement;
        $pdf->SetMargins(10, 30, 10);
        $pdf->addPage('L', 'A4');
        $pdf->view('admin.pdf.billing', compact('invoice'));
        $pdf->stream();
    }



    public function printDHLBillingStatement($id)
    {
        $bill = DHLBill::with('transactions')->findOrFail($id);
        
        $pdf = new BillingStatement;
        $pdf->setTitle('DHL Billing Statement');
        $pdf->SetMargins(10, 30, 10);
        $pdf->addPage('P', 'A4');
        $pdf->view('admin.pdf.dhl-statement', compact('bill'));
        $pdf->stream();
    }


    public function printTransportCopy(Shipment $shipment)
    {
        $shipment->load(['shipper', 'receiver','service', 'agent']);

        $pdf = new TransportInvoice;
        $pdf->SetMargins(10, 10, 10);
        $pdf->addPage('P', 'A4');
        $pdf->view('admin.pdf.transport-copy', compact('shipment'));
        $pdf->view('admin.pdf.transport-copy', compact('shipment'));
        $pdf->stream();
    }


    public function printBookingReceipt($id)
    {
        $shipment = Shipment::findOrFail($id);

        $pdf = new BillingStatement;
        $pdf->setTitle('Booking Receipt');
        $pdf->SetMargins(10, 30, 10);
        $pdf->addPage('P', 'A4');
        $pdf->view('admin.pdf.booking-receipt', compact('shipment'));
        $pdf->stream();
    }

}
