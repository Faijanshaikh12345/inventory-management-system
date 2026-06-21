@extends('layouts.app')

@section('title', 'Create Sale')
@section('page_title', 'Create Sale')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
    <li class="breadcrumb-item active">Create Sale</li>
@endsection

@section('content')

    <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
        @csrf

        <div class="row">

            <div class="col-md-8">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-shopping-cart mr-2"></i> Sale Items
                        </h3>

                        <button type="button" class="btn btn-success btn-sm" id="add-row">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="items-table">

                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th width="35%">Product</th>
                                        <th width="20%">Qty</th>
                                        <th width="20%">Selling Price</th>
                                        <th width="20%">Total</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items-body">
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">
                                            Grand Total :
                                        </td>
                                        <td id="grand-total" class="font-weight-bold text-success">
                                            ₹0.00
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>

                    </div>

                </div>
            </div>

            <div class="col-md-4">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Invoice Details
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <label>Invoice No</label>
                            <input type="text" class="form-control" value="{{ $invoice_no }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Customer *</label>

                            <select name="customer_id" class="form-control" required>

                                <option value="">
                                    Select Customer
                                </option>

                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Sale Date *</label>

                            <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Paid Amount</label>
                            <input type="number" step="0.01" min="0" name="paid_amount" class="form-control"
                                value="0">
                        </div>

                        <div class="form-group">
                            <label>Status *</label>

                            <select name="status" class="form-control">

                                <option value="completed">
                                    Completed
                                </option>

                                <option value="pending">
                                    Pending
                                </option>

                                <option value="cancelled">
                                    Cancelled
                                </option>

                            </select>
                        </div>

                    </div>

                    <div class="card-footer">

                        <button type="submit" class="btn btn-primary btn-block">

                            <i class="fas fa-save"></i>
                            Save Sale
                        </button>

                        <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-block mt-2">

                            Cancel
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

@endsection

@push('scripts')
    <script>
        const products = @json($products);

        let rowIndex = 0;

        function productOptions(selectedId = '') {
            let options =
                '<option value="">Select Product</option>';

            products.forEach(product => {

                let selected =
                    selectedId == product.id ? 'selected' : '';

                options += `
            <option
                value="${product.id}"
                data-price="${product.selling_price}"
                ${selected}>
                ${product.product_name}
            </option>
        `;
            });

            return options;
        }

        function addRow() {
            let row = `
        <tr>

            <td>
                <select name="product_id[]"
                        class="form-control product-select"
                        required>

                    ${productOptions()}

                </select>
            </td>

            <td>
                <input type="number"
                       name="quantity[]"
                       class="form-control qty-input"
                       min="1"
                       value="1"
                       required>
            </td>

            <td>
                <input type="number"
                       name="selling_price[]"
                       class="form-control price-input"
                       step="0.01"
                       min="0"
                       value="0"
                       required>
            </td>

            <td>
                <span class="row-total">
                    ₹0.00
                </span>
            </td>

            <td>
                <button type="button"
                        class="btn btn-danger btn-sm remove-row">

                    <i class="fas fa-times"></i>

                </button>
            </td>

        </tr>
    `;

            $('#items-body').append(row);
        }

        function updateRowTotal(row) {
            let qty =
                parseFloat($(row).find('.qty-input').val()) || 0;

            let price =
                parseFloat($(row).find('.price-input').val()) || 0;

            let total = qty * price;

            $(row)
                .find('.row-total')
                .text('₹' + total.toFixed(2));
        }

        function updateGrandTotal() {
            let grandTotal = 0;

            $('#items-body tr').each(function() {

                let qty =
                    parseFloat($(this).find('.qty-input').val()) || 0;

                let price =
                    parseFloat($(this).find('.price-input').val()) || 0;

                grandTotal += qty * price;
            });

            $('#grand-total')
                .text('₹' + grandTotal.toFixed(2));
        }

        $(document).on('change', '.product-select', function() {

            let price =
                $(this).find(':selected').data('price') || 0;

            $(this)
                .closest('tr')
                .find('.price-input')
                .val(price);

            updateRowTotal($(this).closest('tr'));
            updateGrandTotal();
        });

        $(document).on('input', '.qty-input, .price-input', function() {

            updateRowTotal($(this).closest('tr'));
            updateGrandTotal();
        });

        $(document).on('click', '.remove-row', function() {

            $(this).closest('tr').remove();

            updateGrandTotal();
        });

        $('#add-row').click(function() {
            addRow();
        });

        $(document).ready(function() {
            addRow();
        });
    </script>
@endpush
