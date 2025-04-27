@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>Provider: {{ $provider->name }}
                    <a href="{{ route('vendor.edit', $provider->id) }}" class="btn btn-default pull-right">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('vendor.recharge.create', ['vendor_id' => $provider->id]) }}"
                       class="btn btn-success pull-right" style="margin-right: 5px">
                        <i class="fa fa-plus-circle"></i> Add balance
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
                            class="@if(request()->has('tab') && request()->input('tab') == 'accounts') active @endif">
                            <a href="#accounts" aria-controls="accounts" role="tab" data-toggle="tab">Account</a>
                        </li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'balances') active @endif">
                            <a href="#balances" aria-controls="balances" role="tab" data-toggle="tab">Balances</a>
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
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'accounts') active in @endif"
                             id="accounts">
                            @dd($provider->cash)
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $provider->cash->id }}</td>
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
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'deposits') active in @endif"
                             id="deposits">
                            <table class="table table-striped" id="rechargeHistory">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Account No.</th>
                                    <th>Voucher No.</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Recharge at</th>
                                    <th><i class="fa fa-cogs"></i></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'products') active in @endif"
                             id="products">
                            <table class="table table-striped" id="rechargeHistory">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Account No.</th>
                                    <th>Voucher No.</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Recharge at</th>
                                    <th><i class="fa fa-cogs"></i></th>
                                </tr>
                                </thead>
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
                    'url': "{{ route('vendor.recharge.index') }}",
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
                    {"data": 'account_number'},
                    {"data": 'reference_number'},
                    {"data": 'amount'},
                    {"data": 'balance'},
                    {"data": 'created_at'},
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
                $.ajax({
                    type: "delete",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (data, status, response) {
                        defaultToast();
                        table.draw();
                    }
                })
                return false;
            });
        });
    </script>
@endsection
