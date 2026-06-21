{{-- resources/views/categories/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
    <li class="breadcrumb-item active">Edit Category</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-tags mr-2"></i> Category Information
                    </h3>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

                <form action="{{ route('categories.update', $categories->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Category Name --}}
                        <div class="form-group">
                            <label for="category_name">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="category_name"
                                   id="category_name"
                                   class="form-control @error('category_name') is-invalid @enderror"
                                   placeholder="Enter category name"
                                   value="{{ old('category_name', $categories->category_name) }}">
                            @error('category_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Category Code --}}
                        <div class="form-group">
                            <label for="category_code">
                                Category Code <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="category_code"
                                   id="category_code"
                                   class="form-control @error('category_code') is-invalid @enderror"
                                   placeholder="Enter category code"
                                   value="{{ old('category_code', $categories->category_code) }}">
                            @error('category_code')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description">
                                Description <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="description"
                                   id="description"
                                   class="form-control @error('description') is-invalid @enderror"
                                   placeholder="Enter description"
                                   value="{{ old('description', $categories->description) }}">
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
                                <option value="active" {{ old('status', $categories->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $categories->status) == 'inactive' ? 'selected' : '' }}>
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
                            <i class="fas fa-save mr-1"></i> Update Category
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary ml-2">
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