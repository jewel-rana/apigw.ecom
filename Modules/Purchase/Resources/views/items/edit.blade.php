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
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Edit Purchase Item'}}</h3>
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
                            <form action="{{ route("purchase.item.update",$purchaseItem->id) }}" method="POST">
                                @method('put')
                                @csrf
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

                                        <div class="form-group @error('operator_id') has-error @enderror">
                                                <label for="operator">Operator (*)</label>
                                                <select name="operator_id" class="form-control" id="operator" onchange="fetchBundles()">
                                                    @foreach($operators as $operator)
                                                        <option value="{{ $operator->id }}" {{ $purchaseItem->operator_id == $operator->id ? 'selected' : '' }} >{{ $operator->name }}</option>
                                                    @endforeach
                                                </select>
                                            @error('operator_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('bundle_id') has-error @enderror">
                                            <label for="bundle">Bundle</label><br>
                                            <select name="bundle_id" class="form-control" id="bundle" onchange="fetchBundlePrice(this)">
                                                @if($purchaseItem->bundle_id)
                                                <option value="{{ $purchaseItem->bundle_id }}">{{ $purchaseItem->bundle->name  }}</option>
                                                @endif
                                            </select>
                                            @error('bundle_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('unit_price') has-error @enderror">
                                            <label for="unitPrice">Unit Price (*)</label>
                                            <input type="text" class="form-control" name="unit_price" value="{{ $purchaseItem->unit_price }}" id="unitPrice" onchange="calculateAmount()">
                                            @error('unit_price')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                            <div class="form-group @error('quantity') has-error @enderror">
                                                <label for="quantity">Quantity (*)</label>
                                                <input type="number" class="form-control" min="1" name="quantity" id="quantity" value="{{ $purchaseItem->quantity }}" onchange="calculateAmount()">
                                                @error('quantity')
                                                <div class="help-block with-errors text-danger"><i
                                                        class="fa fa-times"></i> {{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group @error('amount') has-error @enderror">
                                                <label for="amount">Amount (*)</label>
                                                <input type="text" class="form-control" value="{{ $purchaseItem->amount }}" name="amount" id="amount">
                                                @error('amount')
                                                <div class="help-block with-errors text-danger"><i
                                                        class="fa fa-times"></i> {{ $message }}</div>
                                                @enderror
                                            </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">UPDATE</button>
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
        function fetchBundles() {
            $("#bundle").select2({
                allowClear: true,
                width: "element",
                placeholder: "Select bundle",
                delay: 250,
                ajax: {
                    url: '{{ route('bundle.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        let query = {
                            term: params.term,
                            operator_id: $('#operator').val()
                        }
                        return query;
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            })
        }

        function fetchBundlePrice(selectElement){
            const bundleId = selectElement.value;
            const unitPrice = document.getElementById(`unitPrice`);
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

        function calculateAmount() {
            const quantity = document.getElementById(`quantity`).value;
            const unitPrice = document.getElementById(`unitPrice`).value;
            const amountField = document.getElementById(`amount`);

            const amount = quantity * unitPrice;
            amountField.value = amount.toFixed(2);
        }
    </script>
@endsection
