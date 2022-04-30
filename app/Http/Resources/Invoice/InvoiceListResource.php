<?php
 
namespace App\Http\Resources\Invoice;

use Illuminate\Http\Resources\Json\JsonResource;
 
class InvoiceListResource extends JsonResource
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
            'date'              => $this->date->format('Y-m-d'),
            'customer_name'     => $this->customer_name,
            'total_amount'      => $this->products()->sum('price'),
        ];
    }
}