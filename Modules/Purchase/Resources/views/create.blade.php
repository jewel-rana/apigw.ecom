@extends('metis::layouts.master')

@section('header')
    <style>
        .input-row {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .input-row input {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Add new purchase'}}</h3>
                <div>
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="home">

                            <form id="purchaseForm" method="POST">
                                <div class="row">
                                    <div class="col-lg-7">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group @error('provider_id') has-error @enderror">
                                            <label for="">Supplier (*)</label>
                                            <select name="provider_id" class="form-control" >
                                                <option selected disabled>Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                            @if(old('provider_id') == $supplier->id) selected @endif>{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('provider_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('status') has-error @enderror">
                                            <label for="">Status (*)</label>
                                            <select name="status" class="form-control" required>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status }}"
                                                            @if(old('status') == $status) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('currency') has-error @enderror">
                                            <label for="">Base Currency? (*)</label>
                                            <select name="currency" class="form-control" required>
                                                <option value="">Select currency</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency['key'] }}"
                                                            @if(old('currency', 'iqd') == $currency['key']) selected @endif>{{ $currency['value'] }}
                                                        ({{ $currency['symbol'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('currency')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('exchange_rate') has-error @enderror">
                                            <label for="">Exchange Rate?</label>
                                            <input type="text" name="exchange_rate" class="form-control" value="{{ old('exchange_rate') }}">
                                            @error('exchange_rate')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <h4 style="margin-top: 25px;">Purchase Items</h4>
                                        <table class="table table-responsive table-striped" id="dynamicTable">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="operator">Operator (*)</label>
                                                        <select name="item[operator_id][]" class="form-control" id="operator1" onchange="fetchBundles(this, 1)">
                                                            <option value="">Select Operator</option>
                                                            @foreach($operators as $operator)
                                                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="bundle">Bundle</label><br>
                                                        <select name="item[bundle_id][]" class="form-control" id="bundle1" onchange="fetchBundlePrice(this, 1)">
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="unitPrice">Unit Price (*)</label>
                                                        <input type="text" class="form-control" name="item[unit_price][]" id="unitPrice1" onchange="calculateAmount(1)">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="quantity">Quantity (*)</label>
                                                        <input type="number" class="form-control" min="1" name="item[quantity][]" id="quantity1" onchange="calculateAmount(1)">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <label for="amount">Amount (*)</label>
                                                        <input type="text" class="form-control" name="item[amount][]" id="amount1">
                                                    </div>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="3">
                                                    Total
                                                </td>
                                                <td>
                                                    <input type="text" readonly class="form-control" name="quantity" id="totalQuantity">
                                                </td>
                                                <td>
                                                    <input type="text" readonly class="form-control" name="amount" id="totalAmount">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <button type="button" class="btn btn-info" onclick="addNewRow()"><i class="fa fa-plus"></i> Add More</button>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">CREATE</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.inner -->
        </div>
        <!-- /.outer -->
    </div>
    <!-- /#content -->
@endsection
@section('footer')
    <script>
        function addNewRow() {
            let index = document.querySelectorAll("#dynamicTable tbody tr").length + 1;
            var newRow = document.createElement("tr");

            newRow.innerHTML = `
                <td>
                    <div class="form-group">
                        <label for="operator">Operator (*)</label>
                        <select name="item[operator_id][]" class="form-control" id="operator${index}" onchange="fetchBundles(this, ${index})">
                            <option value="">Select Operator</option>
@foreach($operators as $operator)
            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
@endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="bundle">Bundle</label><br>
                        <select name="item[bundle_id][]" class="form-control" id="bundle${index}" onchange="fetchBundlePrice(this, ${index})">
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="unitPrice">Unit Price (*)</label>
                        <input type="text" class="form-control" name="item[unit_price][]" id="unitPrice${index}" onchange="calculateAmount(${index})">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="quantity">Quantity (*)</label>
                        <input type="number" class="form-control" min="1" name="item[quantity][]" id="quantity${index}" onchange="calculateAmount(${index})">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="amount">Amount (*)</label>
                        <input type="text" class="form-control" name="item[amount][]" id="amount${index}">
                    </div>
                </td>
                <td>
                    <button type="button" onclick="removeRow(this)" class="btn btn-sm btn-danger" style="margin-top: 25px;"><i class="fa fa-trash"></i></button>
                </td>
            `;

            document.querySelector("#dynamicTable tbody").appendChild(newRow);
        }

        function calculateTotals() {
            let quantities = document.getElementsByName('item[quantity][]');
            let amounts = document.getElementsByName('item[amount][]');
            let totalQuantity = 0;
            let totalAmount = 0;
            for (let i = 0; i < quantities.length; i++) {
                if (quantities[i].value) {
                    totalQuantity += parseInt(quantities[i].value);
                }
                if (amounts[i].value) {
                    totalAmount += parseFloat(amounts[i].value);
                }
            }
            document.getElementById('totalQuantity').value = totalQuantity;
            document.getElementById('totalAmount').value = totalAmount.toFixed(2);
        }

        function removeRow(button) {
            var row = button.closest("tr");
            document.querySelector("#dynamicTable tbody").removeChild(row);
            calculateTotals();
        }

        function fetchBundles(selectElement, index) {
            $("#bundle"+index).select2({
                allowClear: true,
                width: "120px",
                placeholder: "Select bundle",
                delay: 250,
                ajax: {
                    url: '{{ route('bundle.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        let query = {
                            term: params.term,
                            operator_id: $('#operator'+index).val()
                        }
                        return query;
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            })
        }

        function fetchBundlePrice(selectElement, index){
            const bundleId = selectElement.value;
            const unitPrice = document.getElementById(`unitPrice${index}`);
            if (bundleId) {
                fetch(`/dashboard/bundle/info/${bundleId}`)
                    .then(response => response.json())
                    .then(data => {
                        unitPrice.value = data['buying_price'];
                    })
                    .catch(error => console.error('Error fetching bundles:', error));
            } else {
                // Clear options if no operator is selected
                unitPrice.value  = '';
            }
        }

        function calculateAmount(index) {
            const quantity = document.getElementById(`quantity${index}`).value;
            const unitPrice = document.getElementById(`unitPrice${index}`).value;
            const amountField = document.getElementById(`amount${index}`);

            const amount = quantity * unitPrice;
            amountField.value = amount.toFixed(2);
            calculateTotals();
        }

        $(document).ready(function() {
            $('#purchaseForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Collect form data
                var formData = new FormData(this);

                // Send AJAX request
                $.ajax({
                    url: '{{ route('purchase.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                    },
                    success: function(response) {
                        defaultToast(true,response.message)
                        window.location.href = '/dashboard/purchase';
                    },
                    error: function(xhr) {
                        defaultToast(false,xhr.responseJSON.message)
                        // Handle error response
                        // var errors = xhr.responseJSON.errors;
                        // var errorHtml = '<ul>';
                        // $.each(errors, function(key, error) {
                        //     errorHtml += '<li>' + error[0] + '</li>';
                        // });
                        // errorHtml += '</ul>';
                        // $('.alert-danger').html(errorHtml).show();
                    }
                });
            });
        });
    </script>
@endsection
