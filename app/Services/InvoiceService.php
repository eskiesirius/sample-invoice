<?php

namespace App\Services;

use App\Models\Customer;
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
			$query->where('first_name','LIKE','%'.$request->search.'%')
			->orWhere('middle_name','LIKE','%'.$request->search.'%')
			->orWhere('last_name','LIKE','%'.$request->search.'%')
			->orWhere('suki_number','LIKE','%'.$request->search.'%');
		}

		return $query->paginate($paginate)->withQueryString();
	}

	/**
	 * @param  array  $data
	 *
	 * @return Customer
	 * @throws GeneralException
	 * @throws \Throwable
	 */
	public function store(array $data = []): Customer
	{
		DB::beginTransaction();

		try {
			$invoice = $this->createInvoice([
				'invoice_number' 	=> $data['invoice_number'],
		        'date' 				=> $data['date'],
		        'customer_name' 	=> $data['customer_name'],
            ]);

		} catch (Exception $e) {
			DB::rollBack();

			abort(500);
		}

		DB::commit();

		return $invoice;
	}

	/**
     * @param  array $data
     * @param  Customer  $invoice
     * @return Customer
     *
     * @throws \Throwable
     */
    public function update(array $data = [],Customer $invoice): Customer
    {
        DB::beginTransaction();

        try {
            $invoice->update([
            	'suki_number' 			=> $data['suki_number'] ?? null,
				'first_name' 			=> $data['first_name'] ?? null,
				'middle_name' 			=> $data['middle_name'] ?? null,
				'last_name' 			=> $data['last_name'] ?? null,
				'suffix' 				=> $data['suffix'] ?? null,
				'present_street' 		=> $data['present_street'] ?? null,
				'present_barangay' 		=> $data['present_barangay'] ?? null,
				'present_city' 			=> $data['present_city'] ?? null,
				'present_province' 		=> $data['present_province'] ?? null,
				'present_zip_code' 		=> $data['present_zip_code'] ?? null,
				'permanent_street' 		=> $data['permanent_street'] ?? null,
				'permanent_barangay' 	=> $data['permanent_barangay'] ?? null,
				'permanent_city' 		=> $data['permanent_city'] ?? null,
				'permanent_province' 	=> $data['permanent_province'] ?? null,
				'permanent_zip_code' 	=> $data['permanent_zip_code'] ?? null,
				'birthday' 				=> $data['birthday'] ?? null,
				'birthplace' 			=> $data['birthplace'] ?? null,
				'civil_status' 			=> $data['civil_status'] ?? null,
				'religion' 				=> $data['religion'] ?? null,
				'contact_numbers' 		=> $data['contact_numbers'] ?? null,
				'gender' 				=> $data['gender'] ?? null,
				'is_dumper' 			=> $data['is_dumper'] ?? 0,
				'nationality' 			=> $data['nationality'] ?? null,
				'identification_ids' 	=> $data['identification_ids'] ?? null,
				'nature_of_business' 	=> $data['nature_of_business'] ?? null,
				'who_and_where' 		=> $data['who_and_where'] ?? null,
				'source_funds' 			=> $data['source_funds'] ?? null,
				'type_of_service' 		=> $data['type_of_service'] ?? null,
				'customer_definition' 	=> $data['customer_definition'] ?? null,
				'watchlist_screening' 	=> $data['watchlist_screening'] ?? null,
				'customer_profile' 		=> $data['customer_profile'] ?? null
            ]);

            $this->uploadFile($invoice,'customer_picture');
            $this->uploadFile($invoice,'id_picture');
            $this->uploadFile($invoice,'customer_signature');

        } catch (Exception $e) {
            DB::rollBack();

            throw new GeneralException('There was a problem updating this customer. Please try again.');
        }

        DB::commit();

        return $invoice;
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
		]);
	}

	/**
	 * @param  Customer $invoice 
	 * @param  string   $key      
	 * @return void             
	 */
	protected function uploadFile(Customer $invoice,$key)
	{
		if (empty(request()->file($key)))
			return;

		$invoice->clearMediaCollection($key);

		$name = md5(\Str::uuid());
        $ext = request()->file($key)->extension();

        $invoice->addMediaFromRequest($key)
        	->preservingOriginal()
            ->usingName($name)
            ->usingFileName("$name.$ext")
            ->toMediaCollection($key);
	}

	/**
	 * @param  Customer $invoice 
	 * @param  string   $key      
	 * @param  string   $path  
	 * @return void             
	 */
	public function uploadFileByPath(Customer $invoice,$key, $path)
	{
		$invoice->clearMediaCollection($key);

		$name = md5(\Str::uuid());

        $invoice->addMedia($path)
        	->preservingOriginal()
            ->usingName($name)
            ->usingFileName("$name.png")
            ->toMediaCollection($key);
	}
}
