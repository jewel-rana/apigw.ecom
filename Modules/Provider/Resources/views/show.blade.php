@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>Provider: {{ $provider->name }}
                    <a href="{{ route('provider.edit', $provider->id) }}" class="btn btn-default">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{ route('provider.cash.create', ['provider_id' => $provider->id]) }}"
                       class="btn btn-default pull-right" style="margin-right: 5px">
                        <i class="fa fa-money"></i> {{ $provider->balance }} IQD
                    </a>
                </h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#info" aria-controls="info" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a></li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'deposits') active @endif">
                            <a href="#deposits" aria-controls="deposits" role="tab" data-toggle="tab">Deposits</a>
                        </li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'products') active @endif">
                            <a href="#products" aria-controls="products" role="tab" data-toggle="tab">Products</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel"
                             class="tab-pane fade @if(!request()->has('tab') || request()->input('tab') == 'info') active in @endif"
                             id="info">
                            <div class="row">
                                <div class="col-md-9">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th style="width: 30%">ID</th>
                                            <td>{{ $provider->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $provider->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $provider->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>{{ $provider->nice_status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created at</th>
                                            <td>{{ $provider->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last updated at</th>
                                            <td>{{ $provider->updated_at }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3" id="providerBalanceSection">
                                    <h4>BALANCE</h4>
                                    <span><i class="fa fa-2x"
                                             id="providerBalance">{{ $provider->balance }}</i> IQD</span>
                                    <hr/>
                                    <a href="{{ route('provider.cash.create', ['provider_id' => $provider->id]) }}"><i
                                            class="fa fa-plus"></i> New deposit</a>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'deposits') active in @endif"
                             id="deposits">
                            <a href="{{ route('provider.cash.create', ['provider_id' => $provider->id]) }}"
                               class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add new deposit</a>
                            <div class="clearfix"></div>
                            <table class="table table-striped" id="rechargeHistory">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Voucher No.</th>
                                    <th>Amount</th>
                                    <th>Amount in IQD</th>
                                    <th>Previous balance</th>
                                    <th>After balance</th>
                                    <th>Recharge at</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'products') active in @endif"
                             id="products">
                            <button class="btn btn-primary pull-right" id="tagProduct"><i class="fa fa-tag"></i> Tag
                                product
                            </button>
                            <div class="clearfix"></div>
                            <table class="table table-striped" id="productTable">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Operator</th>
                                    <th>Bundle</th>
                                    <th>Amount</th>
                                    <th><i class="fa fa-cogs"></i></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
    <script src="{{ asset('lib/DataTables/datatables.js') }}"></script>
    <script>
        $(function () {
            let attTable = $('#rechargeHistory').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('provider.cash.index') }}",
                    pages: 5, // number of pages to cache
                    'data': function (data) {
                        // Read values
                        data.provider_id = "{{ $provider->id }}";
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active", dom: 'lrtip',
                "lengthChange": false,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 25,
                "bFilter": false,
                "bInfo": false,
                "searching": false,
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'id'},
                    {"data": 'voucher_number'},
                    {"data": 'amount'},
                    {"data": 'amount_iqd'},
                    {"data": 'previous_balance'},
                    {"data": 'current_balance'},
                    {"data": 'created_at'}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });

            let productTable = $('#productTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('provider.product.index') }}",
                    pages: 5, // number of pages to cache
                    'data': function (data) {
                        // Read values
                        data.provider_id = "{{ $provider->id }}";
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active", dom: 'lrtip',
                "lengthChange": false,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 25,
                "bFilter": false,
                "bInfo": false,
                "searching": false,
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'id'},
                    {"data": 'operator'},
                    {"data": 'name'},
                    {"data": 'face_value'},
                    {"data": "actions", order: false}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });



            $('table').on('click', '.deleteBtn', function () {
                let url = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    console.log(result);
                    if (result.value === true) {
                        $.ajax({
                            type: "delete",
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function (data, status, response) {
                                defaultToast();
                                productTable.draw();
                            }
                        })
                    }
                });
                return false;
            });

            $('#tagProduct').click(function () {
                let url = "{{ route('provider.product.store') }}";
                $(modal).find('.modal-title').text("Tag product");
                $(modal).find('#modalForm').attr('action', url);
                $(modal).find('.modal-body').html(
                    "<input type='hidden' value='{{ $provider->id }}' name='provider_id'>" +
                    "<div class='form-group'>" +
                    "<label>Select operator</label>" +
                    "<select name='operator_id' class='form-control' id='operatorIdSelect' style='width:100%' required>" +
                    "</select>" +
                    "</div>" +
                    "<div class='form-group'>" +
                    "<label>Select bundle</label>" +
                    "<select name='bundle_id' class='form-control' id='bundleIdSelect' style='width:100%' required>" +
                    "</select>" +
                    "</div>"
                );
                initializeSelect2();
                $(modal).modal('show');
            });

            $( document ).on( "ajaxComplete", function( event, xhr, settings ) {
                if(settings.type == 'POST') {
                    if (settings.url == "/transaction/action") {
                        table.draw();
                    }
                    productTable.draw();
                }
            } );

            function initializeSelect2() {
                $('#operatorIdSelect').select2({
                    allowClear: true,
                    placeholder: "Select a operator",
                    delay: 250,
                    ajax: {
                        url: '{{ route('operator.suggestion') }}',
                        dataType: 'json',
                        data: function (params) {
                            let query = {
                                term: params.term
                            }
                            return query;
                        },
                        results: function (data, page) {
                            return {results: data.data};
                        }
                    }
                })

                $('#bundleIdSelect').select2({
                    allowClear: true,
                    placeholder: "Select a bundle",
                    delay: 250,
                    ajax: {
                        url: '{{ route('bundle.suggestion') }}',
                        dataType: 'json',
                        data: function (params) {
                            let query = {
                                term: params.term,
                                operator_id: $('#operatorIdSelect').val(),
                                provider_id: {{ $provider->id }}
                            }
                            return query;
                        },
                        results: function (data, page) {
                            return {results: data.data};
                        }
                    }
                })
            }
        });
    </script>
@endsection
