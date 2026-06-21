{{-- resources/views/customers.blade.php --}}

@extends('layouts.app')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i> Customer List
                    </h3>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add Customer
                    </a>
                </div>

                {{-- Card Body --}}
                <div class="card-body">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <table id="customersTable" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $index => $customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->mobile }}</td>
                                    <td>
                                        @if($customer->status === 'active')
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-check-circle mr-1"></i> Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">
                                                <i class="fas fa-times-circle mr-1"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer->id) }}"
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              id="delete-form-{{ $customer->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete"
                                                    onclick="confirmDelete({{ $customer->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-inbox mr-1"></i> No customers found.
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
            $('#customersTable').DataTable({
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
                    targets: [5]
                }],
                language: {
                    search: '',
                    searchPlaceholder: 'Search categories...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ categories',
                    infoEmpty: 'No categories available',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    zeroRecords: 'No matching categories found',
                    emptyTable: 'No categories found',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this category?\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush