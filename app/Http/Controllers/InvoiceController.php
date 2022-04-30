<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Resources\Invoice\InvoiceEditResource;
use App\Http\Resources\Invoice\InvoiceListResource;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * @var InvoiceService
     */
    protected $invoiceService;

    /**
     * InvoiceService constructor.
     *
     * @param  InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = $this->invoiceService->search($request);
        
        return Inertia::render('Invoice/Index', [
            'invoices' => InvoiceListResource::collection($invoices),
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Inertia
     */
    public function create()
    {
        return Inertia::render('Invoice/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Invoice\CreateInvoiceRequest  $request
     * @return Inertia
     */
    public function store(CreateInvoiceRequest $request)
    {
        $this->invoiceService->store($request->validated());

        return redirect()->route('invoices.index')->banner('Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Invoice $invoice
     * @return Inertia
     */
    public function show(Invoice $invoice)
    {
        return Inertia::render('Invoice/Show',[
            'invoice' => new InvoiceResource($invoice),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Invoice $invoice
     * @return Inertia
     */
    public function edit(Invoice $invoice)
    {
        return Inertia::render('Invoice/Edit',[
            'invoice' => new InvoiceEditResource($invoice),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Invoice\UpdateInvoiceRequest  $request
     * @param  Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request,Invoice $invoice)
    {
        $this->invoiceService->update($request->validated(),$invoice);

        return redirect()->route('invoices.index')->banner('Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $this->invoiceService->deleteById($invoice->id);

        return redirect()->route('invoices.index')->banner('Invoice deleted successfully.');
    }
}
