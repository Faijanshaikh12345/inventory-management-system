<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->where('status', 'active')
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('product_code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('product_name')->get();

        $categories = Category::where('status', 'active')
            ->where('user_id', Auth::id())
            ->orderBy('category_name')
            ->get();

        $totalProducts = $products->count();
        $totalStockValue = $products->sum(fn($p) => $p->stock_qty * $p->purchase_price);
        $lowStockProducts = $products->where('stock_qty', '<=', 10)->count();

        return view('stockReport', compact(
            'products',
            'categories',
            'totalProducts',
            'totalStockValue',
            'lowStockProducts'
        ));
    }


    public function purchase(Request $request)
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');

        $query = Purchase::with(['supplier', 'items.product'])
            ->where('user_id', Auth::id())
            ->whereBetween('purchase_date', [$fromDate, $toDate]);

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchases = $query->orderBy('purchase_date', 'desc')->get();

        $totalPurchases = $purchases->count();
        $totalAmount = $purchases->sum('total_amount');
        $totalItemsBought = $purchases->sum(fn($p) => $p->items->sum('quantity'));

        $suppliers = Supplier::where('status', 'active')
            ->where('user_id', Auth::id())
            ->orderBy('supplier_name')
            ->get();

        return view('purchaseReport', compact(
            'purchases',
            'suppliers',
            'fromDate',
            'toDate',
            'totalPurchases',
            'totalAmount',
            'totalItemsBought'
        ));
    }


    public function sales(Request $request)
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');

        $query = Sale::with(['customer', 'items.product'])
            ->where('user_id', Auth::id())
            ->whereBetween('sale_date', [$fromDate, $toDate]);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('sale_status')) {
            $query->where('sale_status', $request->sale_status);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('grand_total');
        $totalCollected = $sales->sum('paid_amount');
        $totalDue       = $sales->sum(fn($s) => $s->grand_total - $s->paid_amount);
        $totalItemsSold = $sales->sum(fn($s) => $s->items->sum('quantity'));

        $customers = Customer::where('status', 'active')
            ->where('user_id', Auth::id())
            ->orderBy('customer_name')
            ->get();

        return view('salesReport', compact(
            'sales',
            'customers',
            'fromDate',
            'toDate',
            'totalSales',
            'totalRevenue',
            'totalCollected',
            'totalDue',
            'totalItemsSold'
        ));
    }


    public function profitLoss(Request $request)
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');

        $purchasedItems = PurchaseItem::with('product')
            ->whereHas('purchase', function ($q) use ($fromDate, $toDate) {
                $q->where('user_id', Auth::id())
                    ->whereBetween('purchase_date', [$fromDate, $toDate])
                    ->where('status', 'received');
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty_purchased'),
                DB::raw('SUM(total_price) as total_cost')
            )
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');


        $soldItems = SaleItem::with('product')
            ->whereHas('sale', function ($q) use ($fromDate, $toDate) {
                $q->where('user_id', Auth::id())
                    ->whereBetween('sale_date', [$fromDate, $toDate]);
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty_sold'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $productIds = $purchasedItems->keys()
            ->merge($soldItems->keys())
            ->unique();

        $rows = $productIds->map(function ($productId) use ($purchasedItems, $soldItems) {

            $purchase = $purchasedItems->get($productId);
            $sale = $soldItems->get($productId);
            $product = $purchase?->product ?? $sale?->product;

            $qtyPurchased = $purchase?->total_qty_purchased ?? 0;
            $totalCost = $purchase?->total_cost ?? 0;

            $qtySold = $sale?->total_qty_sold ?? 0;
            $totalRevenue = $sale?->total_revenue ?? 0;

            $avgCost = $qtyPurchased > 0
                ? ($totalCost / $qtyPurchased)
                : ($product?->purchase_price ?? 0);

            $costOfGoodsSold = $avgCost * $qtySold;
            $profit = $totalRevenue - $costOfGoodsSold;

            return [
                'product_name' => $product?->product_name ?? '',
                'product_code' => $product?->product_code ?? '',
                'unit' => $product?->unit?->unit_name ?? '',
                'qty_purchased' => $qtyPurchased,
                'qty_sold' => $qtySold,
                'avg_cost' => round($avgCost, 2),
                'total_cost' => round($costOfGoodsSold, 2),
                'total_revenue' => round($totalRevenue, 2),
                'profit' => round($profit, 2),
            ];
        });

        $grandRevenue = $rows->sum('total_revenue');
        $grandCost = $rows->sum('total_cost');
        $grandProfit = $rows->sum('profit');

        return view('profit_loss', compact(
            'rows',
            'fromDate',
            'toDate',
            'grandRevenue',
            'grandCost',
            'grandProfit'
        ));
    }
}
