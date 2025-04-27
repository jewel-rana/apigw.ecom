@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>{{ $customer->name }}
{{--                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-default pull-right">--}}
{{--                        <i class="fa fa-edit"></i> Edit--}}
{{--                    </a>--}}
                </h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a></li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'social') active @endif">
                            <a href="#social" aria-controls="social" role="tab" data-toggle="tab"
                               aria-expanded="true">Social ID</a></li>
                        <li role="presentation"
                            class="@if(request()->has('tab') && request()->input('tab') == 'credential') active @endif">
                            <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Orders</a>
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
                                    <td>{{ $customer->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>{{ $customer->mogile }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ $customer->gender }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $customer->status }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>{{ $customer->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Last updated at</th>
                                    <td>{{ $customer->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'social') active in @endif"
                             id="social">
                            @if($customer->socialites->count())
                                <div class="row">
                                    @foreach($customer->socialites as $socialite)
                                        <div class="col-md-6">
                                            <table class="table table-striped">
                                                <tbody>
                                                <tr>
                                                    <th style="width: 30%">Provider</th>
                                                    <td>{{ strtoupper($socialite?->provider) }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Profile ID</th>
                                                    <td>{{ $socialite->uuid }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Social ID</th>
                                                    <td>{{ $socialite->social_id }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center">No social ID found!</p>
                            @endif
                        </div>
                        <div role="tabpanel"
                             class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'credential') active in @endif"
                             id="profile">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Discount</th>
                                    <th>Coupon Discount</th>
                                    <th>Total Payable</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.inner -->
            </div>
            <!-- /.outer -->
        </div>
    </div>
    <!-- /#content -->
@endsection

@section('footer')
    <script>
        jQuery(function ($) {
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "bAutoWidth": false,
                "sPageButtonActive": "active",
                ajax: {
                    url: '{{ route('order.index') }}',
                    'data': function (data) {
                        // Read values
                        data.customer_id = {{ $customer->id }};
                    }
                },
                columns: [
                    {data: "id"},
                    {data: 'items_count', sortable: false, searchable: false},
                    {data: 'total_amount'},
                    {data: 'discount'},
                    {data: 'coupon_discount'},
                    {data: 'total_payable'},
                    {data: 'status'},
                    {data: 'created_at'}
                ]
            });
        })
    </script>
@endsection
