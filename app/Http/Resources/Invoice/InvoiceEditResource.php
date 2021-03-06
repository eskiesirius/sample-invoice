<?php
 
namespace App\Http\Resources\Invoice;

use Illuminate\Http\Resources\Json\JsonResource;
 
class InvoiceEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id'                => $this->id,
            'invoice_number'    => $this->invoice_number,
            'date'              => $this->date,
            'customer_name'     => $this->customer_name,
            'product_name'      => $this->products()->pluck('name')->toArray(),
            'product_quantity'  => $this->products()->pluck('quantity')->toArray(),
            'product_price'     => $this->products()->pluck('price')->toArray(),
        ];
    }
}