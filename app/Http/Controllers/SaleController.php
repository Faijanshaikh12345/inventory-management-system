<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index()
    {
        $sales = Sale::with('customer')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('sales', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')
            ->where('user_id', Auth::id())
            ->get();

        $products = Product::where('status', 'active')
            ->where('user_id', Auth::id())
            ->get();

        $invoice_no = Sale::generateInvoiceNo();

        return view('create_sale', compact('customers', 'products', 'invoice_no'));
    }

    /**
     * Store a newly created sale and decrease stock.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'          => 'required|exists:customers,id',
            'sale_date'            => 'required|date',
            'status'               => 'required|in:pending,completed,cancelled',
            'paid_amount'          => 'nullable|numeric|min:0',
            'product_id'           => 'required|array|min:1',
            'product_id.*'         => 'required|exists:products,id',
            'quantity'             => 'required|array|min:1',
            'quantity.*'           => 'required|integer|min:1',
            'selling_price'        => 'required|array|min:1',
            'selling_price.*'      => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $grandTotal = 0;

            foreach ($request->product_id as $i => $productId) {

                $qty = $request->quantity[$i];

                $product = Product::findOrFail($productId);

                if ($request->status === 'completed' && $product->stock_qty < $qty) {
                    throw new \Exception(
                        "Insufficient stock for product: {$product->product_name}"
                    );
                }

                $grandTotal += $qty * $request->selling_price[$i];
            }

            $sale = Sale::create([
                'user_id'     => Auth::id(),
                'invoice_no'  => Sale::generateInvoiceNo(),
                'customer_id' => $request->customer_id,
                'sale_date'   => $request->sale_date,
                'grand_total' => $grandTotal,
                'paid_amount' => $request->paid_amount ?? 0,
                'status'      => $request->status,
            ]);

            foreach ($request->product_id as $i => $productId) {

                $qty          = $request->quantity[$i];
                $sellingPrice = $request->selling_price[$i];
                $totalPrice   = $qty * $sellingPrice;

                SaleItem::create([
                    'user_id'       => Auth::id(),
                    'sale_id'       => $sale->id,
                    'product_id'    => $productId,
                    'quantity'      => $qty,
                    'selling_price' => $sellingPrice,
                    'total'         => $totalPrice,
                ]);

                if ($request->status === 'completed') {
                    Product::where('id', $productId)
                        ->decrement('stock_qty', $qty);
                }
            }
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale saved and stock updated successfully');
    }

    /**
     * Display sale invoice.
     */
    public function show(Sale $sale)
    {
        if ($sale->user_id != Auth::id()) {
            abort(403);
        }

        $sale->load(['customer', 'items.product']);

        return view('show_sale', compact('sale'));
    }

    /**
     * Delete sale and restore stock.
     */
    public function destroy(Sale $sale)
    {
        if ($sale->user_id != Auth::id()) {
            abort(403);
        }

        DB::transaction(function () use ($sale) {

            $sale->load('items');

            if ($sale->status === 'completed') {

                foreach ($sale->items as $item) {

                    Product::where('id', $item->product_id)
                        ->increment('stock_qty', $item->quantity);
                }
            }

            $sale->items()->delete();
            $sale->delete();
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted and stock restored successfully');
    }
}
