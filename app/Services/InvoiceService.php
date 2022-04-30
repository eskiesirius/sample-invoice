<?php

namespace App\Services;

use App\Models\Invoice;
use App\Exceptions\GeneralException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Zip;

/**
 * Class InvoiceService.
 */
class InvoiceService extends BaseService
{

	/**
	 * InvoiceService constructor.
	 *
	 * @param Invoice $invoice
	 */
	public function __construct(Invoice $invoice)
	{
		$this->model = $invoice;
	}

	/**
	 * @param  \App\Http\Requests\Invoice\ShowInvoiceRequest   $request     
	 * @param  integer $paginate 
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function search($request,$paginate = 10)
	{
		$query = $this->model->query();

		if ($request->search){
			$query->where('invoice_number','LIKE','%'.$request->search.'%');
		}

		return $query->paginate($paginate)->withQueryString();
	}

	/**
	 * @param  array  $data
	 *
	 * @return Invoice
	 * @throws GeneralException
	 * @throws \Throwable
	 */
	public function store(array $data = []): Invoice
	{
		DB::beginTransaction();

		try {
			$invoice = $this->createInvoice([
				'invoice_number' 	=> $data['invoice_number'],
		        'date' 				=> $data['date'],
		        'customer_name' 	=> $data['customer_name'],
		        'user_id'			=> auth()->user()->id,
            ]);

			$this->storeProducts($data,$invoice);
		} catch (Exception $e) {
			DB::rollBack();

			abort(500);
		}

		DB::commit();

		return $invoice;
	}

	/**
     * @param  array $data
     * @param  Invoice  $invoice
     * @return Invoice
     *
     * @throws \Throwable
     */
    public function update(array $data = [],Invoice $invoice): Invoice
    {
        DB::beginTransaction();

        try {
            $invoice->update([
            	'invoice_number' 	=> $data['invoice_number'],
		        'date' 				=> $data['date'],
		        'customer_name' 	=> $data['customer_name'],
            ]);

            $invoice->products()->delete();
            $this->storeProducts($data,$invoice);
        } catch (Exception $e) {
            DB::rollBack();

            abort(500);
        }

        DB::commit();

        return $invoice;
    }

    /**
     * @param  array  $data    
     * @param  Invoice $invoice 
     * @return void           
     */
    protected function storeProducts($data,Invoice $invoice)
    {
    	foreach ($data['product_name'] as $index => $product) {
			$invoice->products()->create([
				'name' 		=> $product,
				'quantity' 	=> $data['product_quantity'][$index],
				'price' 	=> $data['product_price'][$index],
        	]);
		}
    }

	/**
	 * @param  array  $data
	 *
	 * @return Invoice
	 */
	protected function createInvoice(array $data = []): Invoice
	{
		return $this->model::create([
			'invoice_number' 	=> $data['invoice_number'] ?? null,
	        'date' 				=> $data['date'] ?? null,
	        'customer_name' 	=> $data['customer_name'] ?? null,
	        'user_id' 			=> $data['user_id'] ?? null,
		]);
	}
}
