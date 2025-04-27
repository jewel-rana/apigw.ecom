@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>ID #{{ $order->id }}</h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a></li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'item') active @endif">
                            <a href="#orderItems" aria-controls="orderItems" role="tab" data-toggle="tab">Order
                                Items</a>
                        </li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'payments') active @endif">
                            <a href="#payments" aria-controls="payments" role="tab"
                               data-toggle="tab">Payments</a>
                        </li>
                        {{--                        <li role="presentation"--}}
                        {{--                            class="@if(request()->has('tab') && request()->input('tab') == 'transactions') active @endif">--}}
                        {{--                            <a href="#transactions" aria-controls="transactions" role="tab" data-toggle="tab">Transactions</a>--}}
                        {{--                        </li>--}}
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel"
                             class="tab-pane fade @if(!request()->has('tab') || request()->input('tab') == 'info') active in @endif"
                             id="home">
                            <div class="row">
                                <div class="col-md-8">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th style="width: 30%">ID</th>
                                            <td>{{ $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Items</th>
                                            <td>{{ $order->items_count }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Amount</th>
                                            <td>{{ $order->total_amount }}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td>{{ $order->discount }}</td>
                                        </tr>
                                        <tr>
                                            <th>Coupon Discount</th>
                                            <td>{{ $order->coupon_discount }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Payable</th>
                                            <td>{{ $order->total_payable }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>{{ $order->status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created at</th>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last updated at</th>
                                            <td>{{ $order->updated_at }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <h4>Customer Info</h4>
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <td>
                                                <a href="{{ route('customer.show', $order->customer_id) }}">
                                                    {{ $order->customer->name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $order->customer->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mobile</th>
                                            <td>{{ $order->customer->mobile }}</td>
                                        </tr>
                                        @if($order->customer->country)
                                            <tr>
                                                <th>Country</th>
                                                <td>{{ $order->customer->country?->name ?? '' }}</td>
                                            </tr>
                                        @endif

                                        @if($order->customer->city)
                                            <tr>
                                                <th>City</th>
                                                <td>{{ $order->customer->city?->name ?? '' }}</td>
                                            </tr>
                                        @endif
                                        @if($order->address)
                                            <tr>
                                                <th>Address</th>
                                                <td>{{ $order->address ?? '' }}</td>
                                            </tr>
                                        @endif
                                        @if($order->code)
                                            <tr>
                                                <th>Code</th>
                                                <td>{{ $order->code ?? '' }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'items') active in @endif"
                             id="orderItems">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 120px">ID</th>
                                    <th style="width: 120px">TrxID</th>
                                    <th>Operator</th>
                                    <th>Denomination</th>
                                    <th>Params</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Discount</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th><i class="fa fa-cogs"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->order_id . $item->id }}</td>
                                        <td>
                                            <a href="{{ route('operator.show', $item->operator_id) }}">{{ $item->operator->name ?? '---' }}</a>
                                        </td>
                                        <td>
                                            @if($item->bundle)
                                                <span class="wrapText"
                                                      style="width: 320px; word-wrap: break-word;">
                                                <a href="{{ route('bundle.show', $item->bundle_id) }}">
                                                {{ $item->bundle->name }}
                                                </a>
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                if($item->data) {
                                                    foreach($item->data as $k => $v) {
                                                        echo '<p>' . ucwords(str_replace('_', ' ', $k)) . ' : ' . $v . '</p>';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td>{{ $item->unit_price }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>{{ $item->discount + $item->coupon_discount }}</td>
                                        <td>{{ ($item->unit_price * $item->qty) - ($item->discount + $item->coupon_discount) }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button"
                                                        id="about-us" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">

                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="about-us">
                                                    @if(\App\Helpers\CommonHelper::hasPermission(['order-action']))
                                                        {{--                                                        <li><a href="javascript:void;"><i class="fa fa-eye"></i>--}}
                                                        {{--                                                                Info</a></li>--}}
                                                        {{--                                                        <li><a href="javascript:void;"><i class="fa fa-recycle"></i>--}}
                                                        {{--                                                                Re-check</a></li>--}}
                                                        {{--                                                        @if($item->status == \Modules\Order\App\Constant\OrderItemConstant::FAILED)--}}
                                                        {{--                                                            <li><a href="javascript:void;"><i--}}
                                                        {{--                                                                        class="fa fa-exchange"></i> Refund</a></li>--}}
                                                        {{--                                                        @endif--}}
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
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
                        data.order_id = "{{ $order->id }}";
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
