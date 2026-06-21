{{-- resources/views/reports/purchase.blade.php --}}

@extends('layouts.app')

@section('title', 'Purchase Report')
@section('page_title', 'Purchase Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Purchase Report</li>
@endsection

@section('content')

{{-- ── Summary Cards ─────────────────────────────────────────────── --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPurchases }}</h3>
                <p>Total Purchases</p>
            </div>
            <div class="icon"><i class="fas fa-shopping-basket"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3> {{ number_format($totalAmount, 2) }}</h3>
                <p>Total Amount Spent</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalItemsBought }}</h3>
                <p>Total Items Bought</p>
            </div>
            <div class="icon"><i class="fas fa-cubes"></i></div>
        </div>
    </div>
</div>

{{-- ── Filter Card ───────────────────────────────────────────────── --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-filter mr-2"></i> Filter by Date Range
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.purchase') }}">
            <div class="row align-items-end">

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control"
                               value="{{ $fromDate }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control"
                               value="{{ $toDate }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Supplier</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- All Suppliers --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}"
                                    {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->supplier_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- All Status --</option>
                            <option value="received"  {{ request('status') == 'received'  ? 'selected' : '' }}>Received</option>
                            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-1"></i> Generate Report
                </button>
                <a href="{{ route('reports.purchase') }}" class="btn btn-secondary ml-2">
                    <i class="fas fa-redo mr-1"></i> Reset
                </a>
                <button type="button" class="btn btn-success ml-2" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Report Table ──────────────────────────────────────────────── --}}
<div class="card" id="printArea">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-truck mr-2"></i> Purchase Report
        </h3>
        <small class="text-muted">
            Period: <strong>{{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}</strong>
            to <strong>{{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}</strong>
        </small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Purchase Date</th>
                        <th>Supplier</th>
                        <th>Products</th>
                        <th class="text-center">Total Items</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $i => $purchase)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong class="text-primary">{{ $purchase->invoice_no }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                            <td>{{ $purchase->supplier->supplier_name ?? '—' }}</td>
                            <td>
                                @foreach($purchase->items as $item)
                                    <span class="badge badge-light border mb-1">
                                        {{ $item->product->product_name ?? '?' }}
                                        × {{ $item->quantity }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                {{ $purchase->items->sum('quantity') }}
                            </td>
                            <td class="text-right font-weight-bold">
                            {{ number_format($purchase->total_amount, 2) }}
                            </td>
                            <td class="text-center">
                                @php
                                    $badge = match($purchase->status) {
                                        'received'  => 'success',
                                        'pending'   => 'warning',
                                        'cancelled' => 'danger',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge badge-{{ $badge }} px-2 py-1">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                No purchases found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($purchases->isNotEmpty())
                <tfoot class="thead-light">
                    <tr>
                        <th colspan="5" class="text-right">Totals:</th>
                        <th class="text-center">{{ $totalItemsBought }}</th>
                        <th class="text-right"> {{ number_format($totalAmount, 2) }}</th>
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