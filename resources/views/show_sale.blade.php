@extends('layouts.app')

@section('title', 'View Sale')
@section('page_title', 'View Sale')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
    <li class="breadcrumb-item active">View Sale</li>
@endsection

@section('content')

<div class="row">
    <div class="col-12">

        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Sale Invoice
                </h3>

                <div>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>

                    <button onclick="window.print()" class="btn btn-info btn-sm ml-1">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>

                    <form action="{{ route('sales.destroy', $sale->id) }}"
                          method="POST"
                          class="d-inline"
                          id="delete-form-{{ $sale->id }}">
                        @csrf
                        @method('DELETE')

                        <button type="button"
                                class="btn btn-danger btn-sm ml-1"
                                onclick="confirmDelete({{ $sale->id }})">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-6">

                        <table class="table table-sm table-borderless">

                            <tr>
                                <th style="width:150px;">Invoice No</th>
                                <td>
                                    <strong>{{ $sale->invoice_no }}</strong>
                                </td>
                            </tr>

                            <tr>
                                <th>Sale Date</th>
                                <td>{{ $sale->sale_date->format('d M Y') }}</td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>

                                    @if($sale->status == 'completed')

                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Completed
                                        </span>

                                    @elseif($sale->status == 'pending')

                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>

                                    @else

                                        <span class="badge badge-danger px-2 py-1">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Cancelled
                                        </span>

                                    @endif

                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6 text-md-right">

                        <h5 class="text-muted mb-1">
                            Customer
                        </h5>

                        <h4>
                            {{ $sale->customer->customer_name ?? '-' }}
                        </h4>

                        <p class="mb-0 text-muted">
                            {{ $sale->customer->mobile ?? '' }}
                        </p>

                        <p class="mb-0 text-muted">
                            {{ $sale->customer->email ?? '' }}
                        </p>

                        <p class="mb-0 text-muted">
                            {{ $sale->customer->address ?? '' }}
                        </p>

                    </div>

                </div>

                <hr>

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead style="background-color:#343a40;color:#fff;">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Product</th>
                                <th style="width:120px;" class="text-center">Quantity</th>
                                <th style="width:150px;" class="text-right">Selling Price</th>
                                <th style="width:150px;" class="text-right">Total</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($sale->items as $index => $item)

                                <tr>

                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $item->product->product_name ?? '-' }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="text-right">
                                        ₹{{ number_format($item->selling_price, 2) }}
                                    </td>

                                    <td class="text-right">
                                        ₹{{ number_format($item->total, 2) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                        <tfoot>

                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">
                                    Grand Total :
                                </td>

                                <td class="text-right font-weight-bold text-success">
                                    ₹{{ number_format($sale->grand_total, 2) }}
                                </td>
                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')

<script>

function confirmDelete(id)
{
    if(confirm(
        'Are you sure you want to delete this sale?\nStock will be restored automatically.\nThis action cannot be undone.'
    ))
    {
        document.getElementById(
            'delete-form-' + id
        ).submit();
    }
}

</script>

@endpush