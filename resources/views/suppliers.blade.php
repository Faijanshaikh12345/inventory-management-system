{{-- resources/views/suppliers/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Manage Suppliers')
@section('page_title', 'Manage Suppliers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item active">Suppliers</li>
@endsection

@push('styles')
    <style>
        #suppliersTable thead th {
            background-color: #343a40;
            color: #ffffff;
            white-space: nowrap;
            vertical-align: middle;
        }

        .badge-status {
            font-size: .8rem;
            padding: .35em .65em;
            border-radius: .25rem;
        }

        .badge-active {
            background-color: #28a745;
            color: #fff;
        }

        .badge-inactive {
            background-color: #dc3545;
            color: #fff;
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
                        <i class="fas fa-truck mr-2"></i> All Suppliers
                    </h3>
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Supplier
                    </a>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    <table id="suppliersTable" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Supplier Name</th>
                                <th>Company Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>GST No</th>
                                <th style="width: 110px">Status</th>
                                <th style="width: 115px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $index => $supplier)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $supplier->supplier_name }}</td>
                                    <td>{{ $supplier->company_name }}</td>
                                    <td>{{ $supplier->mobile }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->gst_no }}</td>
                                    <td>
                                        @if ($supplier->status === 'active')
                                            <span class="badge badge-status badge-active">
                                                <i class="fas fa-check-circle mr-1"></i> Active
                                            </span>
                                        @else
                                            <span class="badge badge-status badge-inactive">
                                                <i class="fas fa-times-circle mr-1"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">

                                        {{-- View --}}
                                        <a href="{{ route('suppliers.show', $supplier->id) }}"
                                           class="btn btn-info btn-sm btn-action"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                           class="btn btn-warning btn-sm btn-action"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              id="delete-form-{{ $supplier->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-action"
                                                    title="Delete"
                                                    onclick="confirmDelete({{ $supplier->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mr-2"></i> No suppliers found.
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
            $('#suppliersTable').DataTable({
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
                    targets: [7]
                }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search suppliers...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ suppliers',
                    infoEmpty: 'No suppliers available',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    zeroRecords: 'No matching suppliers found',
                    emptyTable: 'No suppliers found',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this supplier?\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush