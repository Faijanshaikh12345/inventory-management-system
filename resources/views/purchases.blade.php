{{-- resources/views/purchases/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Manage Purchases')
@section('page_title', 'Manage Purchases')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item active">Purchases</li>
@endsection

@push('styles')
    <style>
        #purchasesTable thead th {
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

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-shopping-cart mr-2"></i> All Purchases
                    </h3>
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New Purchase
                    </a>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    <table id="purchasesTable" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Invoice No</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Total Amount</th>
                                <th style="width: 110px">Status</th>
                                <th style="width: 115px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $index => $purchase)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $purchase->invoice_no }}</strong>
                                    </td>
                                    <td>{{ $purchase->supplier->supplier_name ?? '—' }}</td>
                                    <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                                    <td>₹{{ number_format($purchase->total_amount, 2) }}</td>
                                    <td>
                                        @if ($purchase->status === 'received')
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-check-circle mr-1"></i> Received
                                            </span>
                                        @elseif ($purchase->status === 'pending')
                                            <span class="badge badge-warning px-2 py-1">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">
                                                <i class="fas fa-times-circle mr-1"></i> Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        {{-- View --}}
                                        <a href="{{ route('purchases.show', $purchase->id) }}"
                                           class="btn btn-info btn-sm btn-action"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              id="delete-form-{{ $purchase->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-action"
                                                    title="Delete"
                                                    onclick="confirmDelete({{ $purchase->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mr-2"></i> No purchases found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- /.card-body --}}

            </div>
            {{-- /.card --}}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            $('#purchasesTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, 'All']
                ],
                order: [[0, 'asc']],
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    targets: [6]
                }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search purchases...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ purchases',
                    infoEmpty: 'No purchases available',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    zeroRecords: 'No matching purchases found',
                    emptyTable: 'No purchases found',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this purchase?\nStock will be reversed automatically.\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush