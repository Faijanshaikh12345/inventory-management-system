{{-- resources/views/products/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Manage Products')
@section('page_title', 'Manage Products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@push('styles')
    <style>
        #productsTable thead th {
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
                        <i class="fas fa-box-open mr-2"></i> All Products
                    </h3>
                    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Product
                    </a>
                </div>

                {{-- Card Body --}}
                <div class="card-body">
                    <table id="productsTable" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Product Name</th>
                                <th>Code</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Supplier</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Stock</th>
                                <th style="width: 110px">Status</th>
                                <th style="width: 115px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->category->category_name ?? '—' }}</td>
                                    <td>{{ $product->unit->unit_name ?? '—' }}</td>
                                    <td>{{ $product->supplier->supplier_name ?? '—' }}</td>
                                    <td>₹{{ number_format($product->purchase_price, 2) }}</td>
                                    <td>₹{{ number_format($product->selling_price, 2) }}</td>
                                    <td>{{ $product->stock_qty }}</td>
                                    <td>
                                        @if ($product->status === 'active')
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
                                        <a href="{{ route('products.show', $product->id) }}"
                                           class="btn btn-info btn-sm btn-action"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('products.edit', $product->id) }}"
                                           class="btn btn-warning btn-sm btn-action"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('products.destroy', $product->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              id="delete-form-{{ $product->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-action"
                                                    title="Delete"
                                                    onclick="confirmDelete({{ $product->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mr-2"></i> No products found.
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
            $('#productsTable').DataTable({
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
                    targets: [10]
                }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search products...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ products',
                    infoEmpty: 'No products available',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    zeroRecords: 'No matching products found',
                    emptyTable: 'No products found',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this product?\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush