<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('customers', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create_customer');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'email'         => 'required|email|unique:customers,email',
            'mobile'        => 'required',
            'status'        => 'required',
        ], [
            'customer_name.required' => 'Customer name is required',
            'email.required'         => 'Email is required',
            'email.email'            => 'Please enter a valid email address',
            'email.unique'           => 'This email is already registered',
            'mobile.required'        => 'Mobile number is required',
            'status.required'        => 'Please select a status',
        ]);

        Customer::create([
            'user_id'       => Auth::id(),
            'customer_name' => $request->customer_name,
            'email'         => $request->email,
            'mobile'        => $request->mobile,
            'address'       => $request->address,
            'status'        => $request->status,
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customers = Customer::where('user_id', Auth::id())
            ->where('id', $customer->id)
            ->firstOrFail();

        return view('show_customer', compact('customers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $customers = Customer::where('user_id', Auth::id())
            ->where('id', $customer->id)
            ->firstOrFail();

        return view('edit_customer', compact('customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'customer_name' => 'required',
            'email'         => 'required|email|unique:customers,email,' . $customer->id,
            'mobile'        => 'required',
            'status'        => 'required',
        ], [
            'customer_name.required' => 'Customer name is required',
            'email.required'         => 'Email is required',
            'email.email'            => 'Please enter a valid email address',
            'email.unique'           => 'This email is already registered',
            'mobile.required'        => 'Mobile number is required',
            'status.required'        => 'Please select a status',
        ]);

        Customer::where('user_id', Auth::id())
            ->where('id', $customer->id)
            ->update([
                'customer_name' => $request->customer_name,
                'email'         => $request->email,
                'mobile'        => $request->mobile,
                'address'       => $request->address,
                'status'        => $request->status,
            ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        Customer::where('user_id', Auth::id())
            ->where('id', $customer->id)
            ->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }
}