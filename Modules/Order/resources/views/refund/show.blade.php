@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>ID #{{ $refund->id }}</h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a>
                        </li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'item') active @endif">
                            <a href="#orderItems" aria-controls="orderItems" role="tab" data-toggle="tab">Refund
                                Items</a>
                        </li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'payments') active @endif">
                            <a href="#payments" aria-controls="payments" role="tab"
                               data-toggle="tab">Payments</a>
                        </li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'transactions') active @endif">
                            <a href="#transactions" aria-controls="transactions" role="tab" data-toggle="tab">Transactions</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel"
                             class="tab-pane fade @if(!request()->has('tab') || request()->input('tab') == 'info') active in @endif"
                             id="home">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td>{{ $refund->id }}</td>
                                </tr>
                                <tr>
                                    <th>Order ID</th>
                                    <td>{{ $refund->order_id }}</td>
                                </tr>
                                <tr>
                                    <th>Refund Items</th>
                                    <td>{{ $refund->items->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Total Paid</th>
                                    <td>{{ $refund->order->total_payable }}</td>
                                </tr>
                                <tr>
                                    <th>Refund Amount</th>
                                    <td>{{ $refund->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $refund->status }}</td>
                                </tr>
                                <tr>
                                    <th>Attempts</th>
                                    <td>{{ $refund->attempts }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>{{ $refund->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Last updated at</th>
                                    <td>{{ $refund->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'items') active in @endif"
                             id="orderItems">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 80px">ID</th>
                                    <th>Operator</th>
                                    <th>Bundle</th>
                                    <th>Unit Price</th>
                                    <th>qty</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($refund->items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <span class="wrapText" style="width: 320px; word-wrap: break-word;">
                                                {{ $item->operator->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="wrapText" style="width: 320px; word-wrap: break-word;">
                                                {{ $item->bundle?->name ?? '---' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->item->unit_price ?? 0 }}</td>
                                        <td>{{ $item->qty ?? 1 }}</td>
                                        <td>{{ $item->amount }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'payments') active in @endif"
                             id="payments">
                            <table class="table table-striped" id="paymentTable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Gateway</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th><i class="fa fa-cogs"></i></th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'transactions') active in @endif"
                             id="transactions">

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
        $(function () {
            let table = $('#paymentTable').DataTable({
                "processing": true,
                "serverSide": true,
                responsive: true,
                "ajax": {
                    'url': "{{ route('payment.index') }}",
                    pages: 5, // number of pages to cache
                    'data': function (data) {
                        // Read values
                        data.order_id = "{{ $refund->order_id }}";
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
                    {"data": 'gateway.name'},
                    {"data": 'amount'},
                    {"data": 'status'},
                    {"data": 'created_at'},
                    {"data": 'action'}
                ]
            });
        });
    </script>
@endsection
