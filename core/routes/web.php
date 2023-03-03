<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    ShipmentController,
    AgentController,
    ServiceController,
    CompanyController,
    BillingController,
    AgentBillingController,
    InvoiceController,
    DHLBillController,
    MediaController,
    RateController,


    PDFController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::redirect('/mail', 'https://mail.zoho.com/portal/ziaenterprise');

Route::prefix('admin')->as('admin.')->group(function(){
    Auth::routes(['register' => false]);
});


Route::prefix('admin')->as('admin.')->middleware(['auth'])->group(function(){
    Route::get('/', function(){
        return redirect()->route('admin.dashboard');
    })->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    //Shipment routes
    Route::resource('/shipments', ShipmentController::class);
    
    Route::middleware('can:mainbranch')->group(function(){
        Route::resource('/agents', AgentController::class);
        Route::resource('/services', ServiceController::class);
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/companies/sync', [CompanyController::class, 'sync'])->name('companies.sync');
        //update tracking updates
        Route::post('/shipments/{id}/tracking-updates', [ShipmentController::class, 'createTrackingUpdate'])->name('shipments.tracking-updates.create');
        Route::put('/shipments/{id}/tracking-updates', [ShipmentController::class, 'updateTrackingUpdate'])->name('shipments.tracking-updates.update');
        //update reference
        Route::post('/shipments/{id}/update-reference', [ShipmentController::class, 'updateTrackingReference'])->name('shipments.tracking-reference.update');
        Route::post('/shipments/{id}/remove-reference', [ShipmentController::class, 'removeTrackingReference'])->name('shipments.tracking-reference.remove');




     

        Route::controller(InvoiceController::class)->prefix('invoices')->as('invoices.')->group(function(){
            Route::get('/agents', 'agents')->name('agents');
            Route::get('/agents/{id}', 'index')->name('index');
            Route::get('/agents/{id}/create', 'create')->name('create');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('/agents/{id}/generate', 'generate')->name('generate');
            Route::put('/{invoice}/update', 'update')->name('update');
            Route::get('/{id}/show', 'show')->name('show');
            Route::put('/transaction/{id}/update', 'updateTransaction')->name('transaction.update');
            Route::delete('/transaction/{id}/delete', 'deleteTransaction')->name('transaction.delete');
        });
        Route::put('/billing/{id}', [BillingController::class, 'update'])->name('billings.update');



        //DHL Billing
        Route::controller(DHLBillController::class)->prefix('dhl-billings')->as('dhl.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/show/{id}', 'show')->name('show');
            Route::post('/bill/{id}/add-transaction', 'storeTransaction')->name('add-transaction');
        });
        Route::get('media/{id}/download', [MediaController::class, 'download'])->name('media.download');

        //Rates
        Route::controller(RateController::class)->prefix('rates')->as('rates.')->group(function(){
            Route::get('/', 'index')->name('index');            
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/show', 'show')->name('show');

            Route::get('/get-rates', 'getRates')->name('get');
            Route::post('/get-rates', 'searchRates')->name('search');
            Route::get('/add-rates/{rate_chart}', 'addRates')->name('add-rates');


            Route::get('/zone-schemes', 'getZoneSchemes')->name('get-zone-schemes');
            Route::get('/zone-schemes/{id}', 'showZoneScheme')->name('show-zone-scheme');

            Route::get('/zone/{zone}/edit', 'editZone')->name('edit-zone');
            Route::post('/zone/{zone_scheme}/add', 'addZone')->name('add-zone');
            Route::post('/zone/{zone}/update', 'updateZone')->name('update-zone');
            Route::post('/zone/{zone}/store', 'storeZone')->name('store-zone');

            Route::get('/countries', 'getCountries')->name('get-countries');
        });

    });



    //print routes

    Route::controller(PDFController::class)->prefix('pdf')->as('pdf.')->group(function(){
        Route::get('/shipments/{id}/receipt', 'printBookingReceipt')->name('shipments.booking-receipt');
        Route::get('/shipments/{shipment}/transport-copy', 'printTransportCopy')->name('shipments.transport-copy');
        Route::get('/manifests/{manifest}/pdf', 'printManifest')->name('manifests')->can('mainbranch');


        //billing
        Route::get('/invoices/{id}', 'printBillingInvoice')->name('billing-invoice')->can('mainbranch');

        //dhl
        Route::get('/dhl/billing/{id}', 'printDHLBillingStatement')->name('dhl-statement')->can('mainbranch');
    });
});





