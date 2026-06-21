<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'unit', 'supplier'])
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        $units = Unit::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        $suppliers = Supplier::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('create_product', compact('categories', 'units', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'product_code'   => 'required|string|unique:products,product_code',
            'category_id'    => 'required|exists:categories,id',
            'unit_id'        => 'required|exists:units,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_qty'      => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ], [
            'product_name.required'   => 'Product Name is required',
            'product_code.required'   => 'Product Code is required',
            'product_code.unique'     => 'Product Code already exists',
            'category_id.required'    => 'Please select a Category',
            'unit_id.required'        => 'Please select a Unit',
            'supplier_id.required'    => 'Please select a Supplier',
            'purchase_price.required' => 'Purchase Price is required',
            'selling_price.required'  => 'Selling Price is required',
            'stock_qty.required'      => 'Stock Quantity is required',
            'status.required'         => 'Please select a Status',
        ]);

        Product::create([
            'user_id'        => Auth::id(),
            'product_name'   => $request->product_name,
            'product_code'   => $request->product_code,
            'category_id'    => $request->category_id,
            'unit_id'        => $request->unit_id,
            'supplier_id'    => $request->supplier_id,
            'purchase_price' => $request->purchase_price,
            'selling_price'  => $request->selling_price,
            'stock_qty'      => $request->stock_qty,
            'description'    => $request->description,
            'status'         => $request->status,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product = Product::with(['category', 'unit', 'supplier'])
            ->where('user_id', Auth::id())
            ->findOrFail($product->id);

        return view('show_product', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($product->id);

        $categories = Category::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        $units = Unit::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        $suppliers = Supplier::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('edit_product', compact('product', 'categories', 'units', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'product_code'   => 'required|string|unique:products,product_code,' . $product->id,
            'category_id'    => 'required|exists:categories,id',
            'unit_id'        => 'required|exists:units,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_qty'      => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'status'         => 'required|in:active,inactive',
        ], [
            'product_name.required'   => 'Product Name is required',
            'product_code.required'   => 'Product Code is required',
            'product_code.unique'     => 'Product Code already exists',
            'category_id.required'    => 'Please select a Category',
            'unit_id.required'        => 'Please select a Unit',
            'supplier_id.required'    => 'Please select a Supplier',
            'purchase_price.required' => 'Purchase Price is required',
            'selling_price.required'  => 'Selling Price is required',
            'stock_qty.required'      => 'Stock Quantity is required',
            'status.required'         => 'Please select a Status',
        ]);

        $product = Product::where('user_id', Auth::id())
            ->findOrFail($product->id);

        $product->update([
            'product_name'   => $request->product_name,
            'product_code'   => $request->product_code,
            'category_id'    => $request->category_id,
            'unit_id'        => $request->unit_id,
            'supplier_id'    => $request->supplier_id,
            'purchase_price' => $request->purchase_price,
            'selling_price'  => $request->selling_price,
            'stock_qty'      => $request->stock_qty,
            'description'    => $request->description,
            'status'         => $request->status,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product = Product::where('user_id', Auth::id())
            ->findOrFail($product->id);

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}