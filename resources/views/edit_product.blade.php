{{-- resources/views/products/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-box-open mr-2"></i> Product Information
                    </h3>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Product Name --}}
                        <div class="form-group">
                            <label for="product_name">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="product_name"
                                   id="product_name"
                                   class="form-control @error('product_name') is-invalid @enderror"
                                   placeholder="Enter product name"
                                   value="{{ old('product_name', $product->product_name) }}">
                            @error('product_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Product Code --}}
                        <div class="form-group">
                            <label for="product_code">
                                Product Code <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="product_code"
                                   id="product_code"
                                   class="form-control @error('product_code') is-invalid @enderror"
                                   placeholder="Enter product code"
                                   value="{{ old('product_code', $product->product_code) }}">
                            @error('product_code')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="form-group">
                            <label for="category_id">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror"
                                    required>
                                <option value="" disabled>-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Unit --}}
                        <div class="form-group">
                            <label for="unit_id">
                                Unit <span class="text-danger">*</span>
                            </label>
                            <select name="unit_id"
                                    id="unit_id"
                                    class="form-control @error('unit_id') is-invalid @enderror"
                                    required>
                                <option value="" disabled>-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->unit_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Supplier --}}
                        <div class="form-group">
                            <label for="supplier_id">
                                Supplier <span class="text-danger">*</span>
                            </label>
                            <select name="supplier_id"
                                    id="supplier_id"
                                    class="form-control @error('supplier_id') is-invalid @enderror"
                                    required>
                                <option value="" disabled>-- Select Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->supplier_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Purchase Price & Selling Price --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purchase_price">
                                        Purchase Price <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-rupee-sign"></i>
                                            </span>
                                        </div>
                                        <input type="number"
                                               name="purchase_price"
                                               id="purchase_price"
                                               class="form-control @error('purchase_price') is-invalid @enderror"
                                               placeholder="0.00"
                                               step="0.01"
                                               min="0"
                                               value="{{ old('purchase_price', $product->purchase_price) }}">
                                    </div>
                                    @error('purchase_price')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selling_price">
                                        Selling Price <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-rupee-sign"></i>
                                            </span>
                                        </div>
                                        <input type="number"
                                               name="selling_price"
                                               id="selling_price"
                                               class="form-control @error('selling_price') is-invalid @enderror"
                                               placeholder="0.00"
                                               step="0.01"
                                               min="0"
                                               value="{{ old('selling_price', $product->selling_price) }}">
                                    </div>
                                    @error('selling_price')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Stock Quantity --}}
                        <div class="form-group">
                            <label for="stock_qty">
                                Stock Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   name="stock_qty"
                                   id="stock_qty"
                                   class="form-control @error('stock_qty') is-invalid @enderror"
                                   placeholder="Enter stock quantity"
                                   min="0"
                                   value="{{ old('stock_qty', $product->stock_qty) }}">
                            @error('stock_qty')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description"
                                      id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter product description"
                                      rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <label for="status">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select name="status"
                                    id="status"
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="" disabled>-- Select Status --</option>
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    {{-- /.card-body --}}

                    {{-- Card Footer --}}
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Product
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    {{-- /.card-footer --}}

                </form>

            </div>
            {{-- /.card --}}
        </div>
    </div>

@endsection