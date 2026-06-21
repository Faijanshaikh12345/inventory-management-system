{{-- resources/views/reports/stock.blade.php --}}

@extends('layouts.app')

@section('title', 'Stock Report')
@section('page_title', 'Stock Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Stock Report</li>
@endsection

@section('content')

{{-- ── Summary Cards ─────────────────────────────────────────────── --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalProducts }}</h3>
                <p>Total Products</p>
            </div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalStockValue, 2) }}</h3>
                <p>Total Stock Value</p>
            </div>
            <div class="icon"><i class="fas fa-rupee-sign"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $lowStockProducts }}</h3>
                <p>Low Stock (≤ 10)</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

{{-- ── Filter Card ───────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-filter mr-2"></i> Filter
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.stock') }}">
            <div class="row align-items-end">

                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>Search Product</label>
                        <input type="text" name="search"
                               class="form-control"
                               placeholder="Name or Code..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- All Categories --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search mr-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.stock') }}" class="btn btn-secondary">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                    <button type="button" class="btn btn-success ml-2" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ── Report Table ──────────────────────────────────────────────── --}}
<div class="card" id="printArea">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-warehouse mr-2"></i> Current Stock Status
        </h3>
        <small class="text-muted">As of {{ now()->format('d M Y, h:i A') }}</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th class="text-center">Stock Qty</th>
                        <th class="text-right">Buy Price</th>
                        <th class="text-right">Sell Price</th>
                        <th class="text-right">Stock Value</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $i => $product)
                        <tr class="{{ $product->stock_qty <= 10 ? 'table-warning' : '' }}">
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $product->product_name }}</strong></td>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->category->category_name ?? '—' }}</td>
                            <td>{{ $product->unit->unit_name ?? '—' }}</td>
                            <td class="text-center">
                                <strong class="{{ $product->stock_qty <= 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $product->stock_qty }}
                                </strong>
                            </td>
                            <td class="text-right"> {{ number_format($product->purchase_price, 2) }}</td>
                            <td class="text-right"> {{ number_format($product->selling_price, 2) }}</td>
                            <td class="text-right font-weight-bold">
                                 {{ number_format($product->stock_qty * $product->purchase_price, 2) }}
                            </td>
                            <td class="text-center">
                                @if($product->stock_qty <= 0)
                                    <span class="badge badge-danger">Out of Stock</span>
                                @elseif($product->stock_qty <= 10)
                                    <span class="badge badge-warning">Low Stock</span>
                                @else
                                    <span class="badge badge-success">In Stock</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i> No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($products->isNotEmpty())
                <tfoot class="thead-light">
                    <tr>
                        <th colspan="8" class="text-right">Total Stock Value:</th>
                        <th class="text-right"> {{ number_format($totalStockValue, 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    @media print {
        .main-header, .main-sidebar, .content-header,
        .card:not(#printArea), .btn, form { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        #printArea { border: none !important; box-shadow: none !important; }
    }
</style>
@endpush