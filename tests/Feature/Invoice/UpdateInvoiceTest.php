<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class UpdateInvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_update_invoice()
    {
        $user = $this->login();

        $invoice = Invoice::factory()->create();

        InvoiceProduct::factory(5)->create([
            'invoice_id' => $invoice->id
        ]);

        $currentDate = now();

        $response = $this->put(route('invoices.update', ['invoice' => $invoice->id]),[
            'invoice_number'    => '123123',
            'date'              => $currentDate,
            'customer_name'     => 'john doe',
            'product_name'      => ['test product','test product 2'],
            'product_quantity'  => [1,2],
            'product_price'     => [1.00,2.00],
        ]);

        $this->assertDatabaseHas('invoices',[
            'invoice_number'    => '123123',
            'date'              => $currentDate->format('y-m-d'),
            'customer_name'     => 'john doe',
        ]);

        $latestInvoice = Invoice::all()->last();

        $this->assertDatabaseHas('invoice_products',[
            'invoice_id'    => $latestInvoice->id,
            'name'          => 'test product',
            'quantity'      => 1,
            'price'         => 1.00
        ]);

        $this->assertDatabaseHas('invoice_products',[
            'invoice_id'    => $latestInvoice->id,
            'name'          => 'test product 2',
            'quantity'      => 2,
            'price'         => 2.00
        ]);
    }
}
