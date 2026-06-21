<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('suppliers', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create_supplier');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required',
            'company_name'  => 'required',
            'mobile'        => 'required',
            'email'         => 'required',
            'gst_no'        => 'required',
            'status'        => 'required',
        ], [
            'supplier_name.required' => 'Supplier Name is required',
            'company_name.required'  => 'Company Name is required',
            'mobile.required'        => 'Mobile is required',
            'email.required'         => 'Email is required',
            'gst_no.required'        => 'GST No is required',
            'status.required'        => 'Please Select Status',
        ]);

        $unit = Supplier::create([
            'user_id'       => Auth::id(),
            'supplier_name' => $request->supplier_name,
            'company_name'  => $request->company_name,
            'mobile'        => $request->mobile,
            'email'         => $request->email,
            'gst_no'        => $request->gst_no,
            'address'       => $request->address,
            'status'        => $request->status
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier = Supplier::where('user_id', Auth::id())
            ->where('id', $supplier->id)
            ->firstOrFail();

        return view('show_supplier', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $suppliers = Supplier::where('user_id', Auth::id())
            ->where('id', $supplier->id)
            ->firstOrFail();

        return view('edit_supplier', compact('suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'supplier_name' => 'required',
            'company_name'  => 'required',
            'mobile'        => 'required',
            'email'         => 'required',
            'gst_no'        => 'required',
            'status'        => 'required',
        ], [
            'supplier_name.required' => 'Supplier Name is required',
            'company_name.required'  => 'Company Name is required',
            'mobile.required'        => 'Mobile is required',
            'email.required'         => 'Email is required',
            'gst_no.required'        => 'GST No is required',
            'status.required'        => 'Please Select Status',
        ]);

        $supplier = Supplier::where('user_id', Auth::id())
            ->where('id', $supplier->id)
            ->update([
                'supplier_name' => $request->supplier_name,
                'company_name'  => $request->company_name,
                'mobile'        => $request->mobile,
                'email'         => $request->email,
                'gst_no'        => $request->gst_no,
                'address'       => $request->address,
                'status'        => $request->status
            ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier = Supplier::where('user_id', Auth::id())
            ->where('id', $supplier->id)
            ->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier Deleted successfully');
    }
}