@extends('metis::layouts.master')

@section('header')
    <!-- daterange picker -->
    <link rel="stylesheet" href="/lib/daterangepicker/daterangepicker.css">
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light lter">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <header>
                                <div class="icons"><i class="fa fa-table"></i></div>
                                <h5>{{ $title ?? 'Orders' }}</h5>
                                <div class="toolbar">
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table id="dataTable"
                                       class="table table-bordered table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                    <tr>
                                        <th>O.ID</th>
                                        <th>Operator</th>
                                        <th>Bundle</th>
                                        <th>Face value</th>
                                        <th>Sale price</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        <th>Order At</th>
                                        <th>O.Status</th>
                                        <th>P.Status</th>
                                        <th>C.Name</th>
                                        <th>C.ID</th>
                                        <th>C.Mobile</th>
                                        <th class="table-actions">Actions</th>
                                    </tr>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <!--End Datatables-->
            </div>
            <!-- /.inner -->
        </div>
        <!-- /.outer -->
    </div>
    <!-- /#content -->
@endsection

@section('footer')
    <!-- date-range-picker -->
    <script src="/lib/moment/moment.min.js"></script>
    <script src="/lib/daterangepicker/daterangepicker.js"></script>
    <script>
        $(function () {
            let startDate = "{{ date('Y-m-d') }}";
            let endDate = "{{ date('Y-m-d') }}";
            let table = $('#dataTable').DataTable({
                serverSide: true,
                processing:true,
                responsive: true,
                ajax: {
                    url: '{{ route('order.sold') }}',
                    data: function (data) {
                        data.operator_id = $('#filterPanel #operatorId').val();
                        data.bundle_id = $('#filterPanel #bundleId').val();
                        data.customer_id = $('#filterPanel #customerId').val();
                        data.status = $('#filterPanel #status').val();
                        data.payment_status = $('#filterPanel #paymentStatus').val();
                        data.keyword = $('#filterPanel #keywords').val();
                        data.date_from = startDate;
                        data.date_to = endDate;
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active",
                dom: 'lr<"toolbar">tip',
                "lengthChange": true,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 25,
                "bFilter": true,
                "bInfo": true,
                "searching": true,
                "order": [[7, "desc"]],
                columns: [
                    {"data": 'order_id'},
                    {"data": 'operator_name'},
                    {"data": 'bundle_name'},
                    {"data": 'purchase_price'},
                    {"data": 'unit_price'},
                    {"data": 'qty'},
                    {"data": 'total_price'},
                    {"data": 'created_at'},
                    {"data": 'nice_status'},
                    {"data": 'payment_status'},
                    {"data": 'customer_name'},
                    {"data": 'order.customer.id'},
                    {"data": 'order.customer.mobile'},
                    {"data": 'actions'}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });

            document.querySelector('div#dataTable_wrapper .toolbar').innerHTML = "<div class='form-inline' id='filterPanel'>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<input type='text' class='form-control' id='keywords' placeholder='Order ID, Customer ID'>" +
                "</div>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<button type='button' class='form-control btn btn-default float-left ml-0' id='reportrange'>" +
                "<i class='far fa-calendar-alt'></i>" +
                "<span>{{ now()->format('d F, Y') }} - {{ now()->format('d F, Y') }}</span>" +
                "</button>" +
                "</div>@if(\App\Helpers\CommonHelper::hasPermission(['order-product-export']))" +
                "<div class='btn-group pt pr pb pl'>" +
                "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' data-bs-auto-close='outside'><i class='fa fa-filter'></i>" +
                "<span class='caret'></span></button>@endif" +
                "<div class='form-group mx-sm-3 ml'>" +
                "<button class='btn btn-primary' id='orderExport'><i class='fa fa-file-excel-o'></i> Export</button>" +
                "</div>" +
                "<div class='dropdown-menu dropdown-menu-right' style='padding: 10px;'>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='operatorId'></select>" +
                "</div>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='bundleId'></select>" +
                "</div>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='customerId'></select>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

            $("#filterPanel #customerId").select2({
                allowClear: true,
                width: "100%",
                placeholder: "Select customer",
                delay: 250,
                ajax: {
                    url: '{{ route('customer.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            keyword: params.term
                        }
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            });

            $("#filterPanel #operatorId").select2({
                allowClear: true,
                width: "100%",
                placeholder: "Select operator",
                delay: 250,
                ajax: {
                    url: '{{ route('operator.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            keyword: params.term
                        }
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            });

            $("#filterPanel #bundleId").select2({
                allowClear: true,
                width: "100%",
                placeholder: "Select bundle",
                delay: 250,
                ajax: {
                    url: '{{ route('bundle.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            keyword: params.term,
                            operator_id: $('#filterPanel #operatorId').val()
                        }
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            });

            //Date range as a button
            $('#filterPanel #reportrange').daterangepicker(
                {
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('DD MMMM, YYYY') + ' - ' + end.format('DD MMMM, YYYY'));
                    startDate = start.format('YYYY-MM-DD');
                    endDate = end.format('YYYY-MM-DD');
                    table.draw();
                }
            )

            $('#orderExport').click(function() {
                let params = {
                    status: $('#filterPanel #status').val(),
                    payment_status: $('#filterPanel #paymentStatus').val(),
                    operator_id: $('#filterPanel #operatorId').val(),
                    bundle_id: $('#filterPanel #bundleId').val(),
                    customer_id: $('#filterPanel #customerId').val(),
                    keywords: $('#keywords').val(),
                    date_from: startDate,
                    date_to: endDate,
                };
                let queryString = $.param(params);
                window.location.href = '{{ route('order.sold.export') }}' + "?" + queryString;
            })

            $("#filterPanel input").on("keyup", function () {
                table.draw();
            });
            $("#filterPanel select").on("change", function () {
                table.draw();
            });
        });
    </script>
@endsection
