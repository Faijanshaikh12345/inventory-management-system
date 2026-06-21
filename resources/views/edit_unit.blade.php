{{-- resources/views/units/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Edit Unit')
@section('page_title', 'Edit Unit')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Units</a></li>
    <li class="breadcrumb-item active">Edit Unit</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-ruler-combined mr-2"></i> Unit Information
                    </h3>
                    <a href="{{ route('units.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>

                <form action="{{ route('units.update', $units->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Unit Name --}}
                        <div class="form-group">
                            <label for="unit_name">
                                Unit Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="unit_name"
                                   id="unit_name"
                                   class="form-control @error('unit_name') is-invalid @enderror"
                                   placeholder="Enter unit name"
                                   value="{{ old('unit_name', $units->unit_name) }}">
                            @error('unit_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Short Name --}}
                        <div class="form-group">
                            <label for="short_name">
                                Short Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="short_name"
                                   id="short_name"
                                   class="form-control @error('short_name') is-invalid @enderror"
                                   placeholder="Enter short name"
                                   value="{{ old('short_name', $units->short_name) }}">
                            @error('short_name')
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
                                <option value="active" {{ old('status', $units->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $units->status) == 'inactive' ? 'selected' : '' }}>
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
                            <i class="fas fa-save mr-1"></i> Update Unit
                        </button>
                        <a href="{{ route('units.index') }}" class="btn btn-secondary ml-2">
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