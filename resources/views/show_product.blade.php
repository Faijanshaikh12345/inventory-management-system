{{-- resources/views/products/show.blade.php --}}

@extends('layouts.app')

@section('title', 'View Product')
@section('page_title', 'View Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">View Product</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-box-open mr-2"></i> Product Details
                    </h3>
                    <div>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm ml-1">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">

                        <tr>
                            <th style="width: 200px; background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-hashtag mr-2 text-muted"></i> ID
                            </th>
                            <td class="pl-4">{{ $product->id }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-box mr-2 text-muted"></i> Product Name
                            </th>
                            <td class="pl-4">{{ $product->product_name }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-barcode mr-2 text-muted"></i> Product Code
                            </th>
                            <td class="pl-4">{{ $product->product_code }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-tags mr-2 text-muted"></i> Category
                            </th>
                            <td class="pl-4">{{ $product->category->category_name ?? '—' }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-ruler-combined mr-2 text-muted"></i> Unit
                            </th>
                            <td class="pl-4">{{ $product->unit->unit_name ?? '—' }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-truck mr-2 text-muted"></i> Supplier
                            </th>
                            <td class="pl-4">{{ $product->supplier->supplier_name ?? '—' }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-rupee-sign mr-2 text-muted"></i> Purchase Price
                            </th>
                            <td class="pl-4">₹{{ number_format($product->purchase_price, 2) }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-rupee-sign mr-2 text-muted"></i> Selling Price
                            </th>
                            <td class="pl-4">₹{{ number_format($product->selling_price, 2) }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-cubes mr-2 text-muted"></i> Stock Quantity
                            </th>
                            <td class="pl-4">{{ $product->stock_qty }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-align-left mr-2 text-muted"></i> Description
                            </th>
                            <td class="pl-4">{{ $product->description ?? '—' }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-toggle-on mr-2 text-muted"></i> Status
                            </th>
                            <td class="pl-4">
                                @if($product->status === 'active')
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="fas fa-check-circle mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-danger px-2 py-1">
                                        <i class="fas fa-times-circle mr-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-calendar-plus mr-2 text-muted"></i> Created At
                            </th>
                            <td class="pl-4">{{ $product->created_at->format('d M Y, h:i A') }}</td>
                        </tr>

                        <tr>
                            <th style="background: #f8f9fa;" class="pl-4">
                                <i class="fas fa-calendar-check mr-2 text-muted"></i> Updated At
                            </th>
                            <td class="pl-4">{{ $product->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>

                    </table>
                </div>
                {{-- /.card-body --}}

                {{-- Card Footer --}}
                <div class="card-footer">
                    <form action="{{ route('products.destroy', $product->id) }}"
                          method="POST"
                          class="d-inline"
                          id="delete-form-{{ $product->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $product->id }})">
                            <i class="fas fa-trash-alt mr-1"></i> Delete
                        </button>
                    </form>
                </div>
                {{-- /.card-footer --}}

            </div>
            {{-- /.card --}}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this product?\nThis action cannot be undone.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush