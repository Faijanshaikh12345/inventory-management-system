<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases.
     */
    public function index()
    {
        $purchases = Purchase::with('supplier')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('purchases', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $suppliers = Supplier::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        $products = Product::where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        $invoice_no = Purchase::generateInvoiceNo();

        return view('create_purchase', compact('suppliers', 'products', 'invoice_no'));
    }

    /**
     * Store a newly created purchase and increase stock.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'purchase_date'        => 'required|date',
            'status'               => 'required|in:pending,received,cancelled',
            'product_id'           => 'required|array|min:1',
            'product_id.*'         => 'required|exists:products,id',
            'quantity'             => 'required|array|min:1',
            'quantity.*'           => 'required|integer|min:1',
            'unit_price'           => 'required|array|min:1',
            'unit_price.*'         => 'required|numeric|min:0',
        ], [
            'supplier_id.required'   => 'Please select a Supplier',
            'purchase_date.required' => 'Purchase Date is required',
            'status.required'        => 'Please select a Status',
            'product_id.required'    => 'Please add at least one product',
            'quantity.*.min'         => 'Quantity must be at least 1',
            'unit_price.*.min'       => 'Unit price must be 0 or more',
        ]);

        DB::transaction(function () use ($request) {

            $totalAmount = 0;

            foreach ($request->product_id as $i => $productId) {
                $totalAmount += $request->quantity[$i] * $request->unit_price[$i];
            }

            $purchase = Purchase::create([
                'user_id'       => Auth::id(),
                'invoice_no'    => Purchase::generateInvoiceNo(),
                'supplier_id'   => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'total_amount'  => $totalAmount,
                'status'        => $request->status
            ]);

            foreach ($request->product_id as $i => $productId) {

                $qty        = $request->quantity[$i];
                $unitPrice  = $request->unit_price[$i];
                $totalPrice = $qty * $unitPrice;

                PurchaseItem::create([
                    'user_id'     => Auth::id(),
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'quantity'    => $qty,
                    'unit_price'  => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                if ($request->status === 'received') {
                    Product::where('id', $productId)
                        ->increment('stock_qty', $qty);
                }
            }
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase saved and stock updated successfully');
    }

    /**
     * Display a purchase invoice.
     */
    public function show(Purchase $purchase)
    {
        $purchase = Purchase::with(['supplier', 'items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($purchase->id);

        return view('show_purchase', compact('purchase'));
    }

    /**
     * Remove a purchase and reverse stock.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase = Purchase::where('user_id', Auth::id())
            ->findOrFail($purchase->id);

        DB::transaction(function () use ($purchase) {

            $purchase->load('items');

            if ($purchase->status === 'received') {
                foreach ($purchase->items as $item) {
                    Product::where('id', $item->product_id)
                        ->decrement('stock_qty', $item->quantity);
                }
            }

            $purchase->items()->delete();
            $purchase->delete();
        });

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase deleted and stock reversed successfully');
    }
}