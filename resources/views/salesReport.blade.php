{{-- resources/views/reports/sales.blade.php --}}

@extends('layouts.app')

@section('title', 'Sales Report')
@section('page_title', 'Sales Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">Sales Report</li>
@endsection

@section('content')

{{-- ── Summary Cards ─────────────────────────────────────────────── --}}
<div class="row mb-3">
    <div class="col-md-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalSales }}</h3>
                <p>Total Sales</p>
            </div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalRevenue, 2) }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3> {{ number_format($totalCollected, 2) }}</h3>
                <p>Total Collected</p>
            </div>
            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3> {{ number_format($totalDue, 2) }}</h3>
                <p>Total Due</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
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
        <form method="GET" action="{{ route('reports.sales') }}">
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

                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label>Customer</label>
                        <select name="customer_id" class="form-control">
                            <option value="">-- All --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}"
                                    {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->customer_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <label>Status</label>
                        <select name="sale_status" class="form-control">
                            <option value="">-- All --</option>
                            <option value="completed" {{ request('sale_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending"   {{ request('sale_status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search mr-1"></i> Generate Report
                </button>
                <a href="{{ route('reports.sales') }}" class="btn btn-secondary ml-2">
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
            <i class="fas fa-shopping-cart mr-2"></i> Sales Report
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
                        <th>Sale Date</th>
                        <th>Customer</th>
                        <th>Products Sold</th>
                        <th class="text-center">Items</th>
                        <th class="text-right">Grand Total</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Due</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $i => $sale)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong class="text-primary">{{ $sale->invoice_no }}</strong></td>
                            <td>{{ $sale->sale_date->format('d M Y') }}</td>
                            <td>{{ $sale->customer->customer_name ?? '—' }}</td>
                            <td>
                                @foreach($sale->items as $item)
                                    <span class="badge badge-light border mb-1">
                                        {{ $item->product->product_name ?? '?' }}
                                        × {{ $item->quantity }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                {{ $sale->items->sum('quantity') }}
                            </td>
                            <td class="text-right font-weight-bold">
                                 {{ number_format($sale->grand_total, 2) }}
                            </td>
                            <td class="text-right text-success">
                                 {{ number_format($sale->paid_amount, 2) }}
                            </td>
                            <td class="text-right {{ $sale->due_amount > 0 ? 'text-danger' : 'text-muted' }}">
                                 {{ number_format($sale->due_amount, 2) }}
                            </td>
                            
                            <td class="text-center">
                                <span class="badge badge-{{ $sale->status === 'completed' ? 'success' : 'warning' }} px-2 py-1">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                No sales found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($sales->isNotEmpty())
                <tfoot class="thead-light">
                    <tr>
                        <th colspan="5" class="text-right">Totals:</th>
                        <th class="text-center">{{ $totalItemsSold }}</th>
                        <th class="text-right">{{ number_format($totalRevenue, 2) }}</th>
                        <th class="text-right text-success"> {{ number_format($totalCollected, 2) }}</th>
                        <th class="text-right text-danger"> {{ number_format($totalDue, 2) }}</th>
                        <th colspan="2"></th>
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