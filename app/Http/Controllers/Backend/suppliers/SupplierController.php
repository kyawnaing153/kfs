<?php

namespace App\Http\Controllers\Backend\suppliers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Supplier\SupplierRequest;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /*
    * Display a list of suppliers
    */
    public function index(Request $request)
    {
        $filters = [];

        if($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        $suppliers = $this->supplierService->getSuppliers($filters, $orderBy, $orderDir)->paginate(15);

        return view('pages.admin.suppliers.index', compact('suppliers'));
    }

    /*
    * Show the form for creating a new resource.
    */
    public function create()
    {
        return view('pages.admin.suppliers.create');
    }

    /*
    * Store a newly created resource in storage.
    */
    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();

        $this->supplierService->createSupplier($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        return view('pages.admin.suppliers.show', compact('supplier'));
    }

    /*
    * Show the form for editing the specified resource.
    */
    public function edit(string $id)
    {
        $supplier = $this->supplierService->getSupplier($id);
        return view('pages.admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, string $id)
    {
        $validated = $request->validated();

        $this->supplierService->updateSupplier($id, $validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->supplierService->deleteSupplier($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function toggleStatus($id) {
        $this->supplierService->toggleSupplierStatus($id);
        return redirect()->back()->with('success', 'Supplier status toggled successfully.');
    }
}
