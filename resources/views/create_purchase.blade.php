{{-- resources/views/purchases/create.blade.php --}}

@extends('layouts.app')

@section('title', 'Create Purchase')
@section('page_title', 'Create Purchase')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
    <li class="breadcrumb-item active">Create Purchase</li>
@endsection

@section('content')

    <form action="{{ route('purchases.store') }}" method="POST" id="purchase-form">
        @csrf

        <div class="row">

            {{-- Left Column: Invoice Info --}}
            <div class="col-md-8">
                <div class="card">

                    {{-- Card Header --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-shopping-cart mr-2"></i> Purchase Items
                        </h3>
                        <button type="button" class="btn btn-success btn-sm" id="add-row">
                            <i class="fas fa-plus mr-1"></i> Add Product
                        </button>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Items Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered" id="items-table">
                                <thead style="background-color: #343a40; color: #fff;">
                                    <tr>
                                        <th style="width: 35%">Product</th>
                                        <th style="width: 20%">Qty</th>
                                        <th style="width: 25%">Unit Price (₹)</th>
                                        <th style="width: 15%">Total (₹)</th>
                                        <th style="width: 5%" class="text-center">
                                            <i class="fas fa-cog"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                    {{-- Rows added dynamically --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">Grand Total:</td>
                                        <td class="font-weight-bold text-success" id="grand-total">₹0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @error('product_id')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror

                    </div>
                    {{-- /.card-body --}}

                </div>
                {{-- /.card --}}
            </div>

            {{-- Right Column: Purchase Details --}}
            <div class="col-md-4">
                <div class="card">

                    {{-- Card Header --}}
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-file-invoice mr-2"></i> Invoice Details
                        </h3>
                    </div>

                    {{-- Card Body --}}
                    <div class="card-body">

                        {{-- Invoice No --}}
                        <div class="form-group">
                            <label for="invoice_no">Invoice No</label>
                            <input type="text"
                                   id="invoice_no"
                                   class="form-control"
                                   value="{{ $invoice_no }}"
                                   readonly>
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
                                <option value="" disabled selected>-- Select Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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

                        {{-- Purchase Date --}}
                        <div class="form-group">
                            <label for="purchase_date">
                                Purchase Date <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   name="purchase_date"
                                   id="purchase_date"
                                   class="form-control @error('purchase_date') is-invalid @enderror"
                                   value="{{ old('purchase_date', date('Y-m-d')) }}"
                                   required>
                            @error('purchase_date')
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
                                <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>
                                    Received
                                </option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
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
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Save Purchase
                        </button>
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary btn-block mt-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                    {{-- /.card-footer --}}

                </div>
                {{-- /.card --}}
            </div>

        </div>
    </form>

@endsection

@push('scripts')
<script>
    // Products data from controller
    const products = @json($products);

    let rowIndex = 0;

    // Build product options HTML
    function productOptions(selectedId = '') {
        let options = '<option value="" disabled selected>-- Select Product --</option>';
        products.forEach(p => {
            const selected = (p.id == selectedId) ? 'selected' : '';
            options += `<option value="${p.id}" data-price="${p.purchase_price}" ${selected}>${p.product_name}</option>`;
        });
        return options;
    }

    // Add a new product row
    function addRow() {
        const row = `
            <tr id="row-${rowIndex}">
                <td>
                    <select name="product_id[]" class="form-control product-select" required>
                        ${productOptions()}
                    </select>
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control qty-input"
                           placeholder="0" min="1" value="1" required>
                </td>
                <td>
                    <input type="number" name="unit_price[]" class="form-control price-input"
                           placeholder="0.00" step="0.01" min="0" value="0" required>
                </td>
                <td>
                    <span class="row-total font-weight-bold">₹0.00</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>`;
        $('#items-body').append(row);
        rowIndex++;
        updateGrandTotal();
    }

    // Calculate row total
    function updateRowTotal(row) {
        const qty   = parseFloat($(row).find('.qty-input').val()) || 0;
        const price = parseFloat($(row).find('.price-input').val()) || 0;
        const total = qty * price;
        $(row).find('.row-total').text('₹' + total.toFixed(2));
    }

    // Calculate grand total
    function updateGrandTotal() {
        let grand = 0;
        $('#items-body tr').each(function () {
            const qty   = parseFloat($(this).find('.qty-input').val()) || 0;
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            grand += qty * price;
        });
        $('#grand-total').text('₹' + grand.toFixed(2));
    }

    // Auto-fill price when product is selected
    $(document).on('change', '.product-select', function () {
        const price = $(this).find(':selected').data('price') || 0;
        $(this).closest('tr').find('.price-input').val(parseFloat(price).toFixed(2));
        updateRowTotal($(this).closest('tr'));
        updateGrandTotal();
    });

    // Update totals on qty/price change
    $(document).on('input', '.qty-input, .price-input', function () {
        updateRowTotal($(this).closest('tr'));
        updateGrandTotal();
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        updateGrandTotal();
    });

    // Add row button
    $('#add-row').on('click', addRow);

    // Add first row on load
    $(document).ready(function () {
        addRow();
    });

    // Validate at least one product before submit
    $('#purchase-form').on('submit', function (e) {
        if ($('#items-body tr').length === 0) {
            e.preventDefault();
            alert('Please add at least one product.');
        }
    });
</script>
@endpush