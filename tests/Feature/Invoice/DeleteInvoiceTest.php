<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class DeleteInvoiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_delete_invoice()
    {
        $this->login();

        $invoice = Invoice::factory()->create();

        InvoiceProduct::factory(31)->create([
            'invoice_id' => $invoice->id
        ]);

        $response = $this->delete(route('invoices.destroy',['invoice' => $invoice->id]));

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id
        ]);

        $this->assertDatabaseMissing('invoice_products', [
            'invoice_id' => $invoice->id
        ]);
    }
}
