@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>Payment: #{{ $payment->id }}, [Invoice: {{ $payment->order_id }}]</h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a></li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'logs') active @endif">
                            <a href="#paymentLogs" aria-controls="paymentLogs" role="tab" data-toggle="tab">Logs</a>
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
                                    <td>{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Gateway</th>
                                    <td>{{ $payment->gateway->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ $payment->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $payment->status }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>{{ $payment->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Last updated at</th>
                                    <td>{{ $payment->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                            @if($payment->canTakeAction())
                                {{--                                <div class="btn-group">--}}
                                {{--                                    <a href="" class="btn btn-primary"><i class="fa fa-check-circle"></i> Verify</a>--}}
                                {{--                                    <a href="" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>--}}
                                {{--                                </div>--}}
                            @endif
                            <p>Note: After {{\Modules\Payment\App\Constants\PaymentConstant::PROCESSING_PERIOD}} minutes
                                of payment creation action button will be visible here.</p>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'logs') active in @endif"
                             id="paymentLogs">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Gateway trx ID</th>
                                        <th style="width: 20%">Request Payload</th>
                                        <th style="width: 20%">Response Payload</th>
                                        <th>Created at</th>
                                        <th><i class="fa fa-cogs"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payment->logs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>{{ $log->type }}</td>
                                            <td>{{ $log->gateway_payment_id }}</td>
                                            <td>{!! json_encode($log->request_payload) !!}</td>
                                            <td>{!! json_encode($log->response_payload) !!}</td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>
                                                <button class="btn btn-default showPayload"
                                                        data-payload="{{json_encode($log)}}"><i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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
        jQuery(function ($) {
            $('.showPayload').click(function () {
                let data = $(this).data('payload');
                $(modal).find('.modal-title').text('Payment: #' + data.payment_id);
                $(modal).find('.modal-dialog').addClass('modal-dialog-lg');
                $(modal).find('.modal-footer').hide();
                $(modal).find('.modal-body').html(
                    '<table class="table table-striped">' +
                    '<tr>' +
                    '<td>Payment ID</td>' +
                    '<td>: ' + data.order_id + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Order ID</td>' +
                    '<td>: ' + data.order_id + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Type</td>' +
                    '<td>: ' + data.type + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Request Payload</td>' +
                    '<td>: ' + JSON.stringify(data.request_payload, null, 4) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>Response Payload</td>' +
                    '<td>: ' + JSON.stringify(data.response_payload, null, 4) + '</td>' +
                    '</tr>' +
                    '</table>'
                );
                $(modal).modal('show');
            });
        })
    </script>
@endsection
