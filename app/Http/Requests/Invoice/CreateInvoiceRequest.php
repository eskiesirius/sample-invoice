<?php

namespace App\Http\Requests\Invoice;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateInvoiceRequest.
 */
class CreateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_number'        => ['required','string','max:100','unique:invoices'],
            'date'                  => ['required','date'],
            'customer_name'         => ['required','max:100'],
            'product_name.*'        => ['required','max:100'],
            'product_quantity.*'    => ['required','gt:0'],
            'product_price.*'       => ['required','gt:0'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.*.required'       => "Name is require.d",
            'product_quantity.*.required'   => "Quantity is required.",
            'product_price.*.required'      => "Price is required.",

            'product_name.*.max'            => "Name must not be greater than 100.",
            'product_quantity.*.gt'         => "Quantity must be greater than 0.",
            'product_price.*.gt'            => "Price must be greater than 0.",
        ];
    }
}
