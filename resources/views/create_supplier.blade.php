{{-- resources/views/suppliers/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Create Supplier')
@section('page_title', 'Create Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
    <li class="breadcrumb-item active">Create Supplier</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-truck mr-2"></i> Supplier Information
                    </h3>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Supplier Name --}}
                        <div class="form-group">
                            <label for="supplier_name">
                                Supplier Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="supplier_name"
                                   id="supplier_name"
                                   class="form-control @error('supplier_name') is-invalid @enderror"
                                   placeholder="Enter supplier name"
                                   value="{{ old('supplier_name') }}">
                            @error('supplier_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Company Name --}}
                        <div class="form-group">
                            <label for="company_name">
                                Company Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="company_name"
                                   id="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   placeholder="Enter company name"
                                   value="{{ old('company_name') }}">
                            @error('company_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Mobile --}}
                        <div class="form-group">
                            <label for="mobile">
                                Mobile <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="mobile"
                                   id="mobile"
                                   class="form-control @error('mobile') is-invalid @enderror"
                                   placeholder="Enter mobile number"
                                   value="{{ old('mobile') }}">
                            @error('mobile')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Enter email address"
                                   value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- GST No --}}
                        <div class="form-group">
                            <label for="gst_no">
                                GST No <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="gst_no"
                                   id="gst_no"
                                   class="form-control @error('gst_no') is-invalid @enderror"
                                   placeholder="Enter GST number"
                                   value="{{ old('gst_no') }}">
                            @error('gst_no')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Address --}}
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address"
                                      id="address"
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="Enter address"
                                      rows="3">{{ old('address') }}</textarea>
                            @error('address')
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
                                    >
                                <option value="" disabled selected>-- Select Status --</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
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
                            <i class="fas fa-save mr-1"></i> Save Supplier
                        </button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary ml-2">
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