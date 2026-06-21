{{-- resources/views/sales.blade.php --}}

@extends('layouts.app')

@section('title', 'Manage Sales')
@section('page_title', 'Manage Sales')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item active">Sales</li>
@endsection

@push('styles')
    <style>
        #salesTable thead th {
            background-color: #343a40;
            color: #ffffff;
            white-space: nowrap;
            vertical-align: middle;
        }

        .btn-action {
            width: 30px;
            height: 30px;
            padding: 0;
            line-height: 30px;
            text-align: center;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            padding: .375rem .75rem;
            margin-left: .5rem;
        }

        div.dataTables_wrapper div.dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
    </style>
@endpush

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ session('error') }}

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h3 class="card-title mb-0">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        All Sales
                    </h3>

                    <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">

                        <i class="fas fa-plus mr-1"></i>
                        New Sale
                    </a>

                </div>

                <div class="card-body">

                    <table id="salesTable" class="table table-bordered table-striped table-hover w-100">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Sale Date</th>
                                <th>Grand Total</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($sales as $index => $sale)
                                <tr>

                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <strong>{{ $sale->invoice_no }}</strong>
                                    </td>

                                    <td>
                                        {{ $sale->customer->customer_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $sale->sale_date->format('d M Y') }}
                                    </td>

                                    <td>
                                        ₹{{ number_format($sale->grand_total, 2) }}
                                    </td>
                                    <td>{{ number_format($sale->paid_amount, 2) }}</td> {{-- Paid --}}
                                    <td>{{ number_format($sale->due_amount, 2) }}</td> {{-- Due --}}

                                    <td>

                                        @if ($sale->status == 'completed')
                                            <span class="badge badge-success">
                                                Completed
                                            </span>
                                        @elseif($sale->status == 'pending')
                                            <span class="badge badge-warning">
                                                Pending
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                Cancelled
                                            </span>
                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('sales.show', $sale->id) }}"
                                            class="btn btn-info btn-sm btn-action">

                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                            class="d-inline" id="delete-form-{{ $sale->id }}">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-danger btn-sm btn-action"
                                                onclick="confirmDelete({{ $sale->id }})">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="10" class="text-center text-muted">

                                        No Sales Found

                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {

            $('#salesTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10
            });

        });

        function confirmDelete(id) {
            if (confirm(
                    'Are you sure you want to delete this sale?\nStock will be restored automatically.'
                )) {
                document.getElementById(
                    'delete-form-' + id
                ).submit();
            }
        }
    </script>
@endpush
