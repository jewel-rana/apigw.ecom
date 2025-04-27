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
                                <h5>{{ $title ?? 'Payments' }}</h5>
                                <div class="toolbar">
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order ID</th>
                                        <th>Gateway</th>
                                        <th>Total Payable</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                        <th class="table-actions">Actions</th>
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
                    url: '{{ route('payment.index') }}',
                    data: function (data) {
                        data.gateway_id = $('#filterPanel #gatewayId').val();
                        data.status = $('#filterPanel #status').val();
                        data.keyword = $('#filterPanel #keywords').val();
                        data.date_from = startDate;
                        data.date_to = endDate;
                    }
                },
                "bAutoWidth": true,
                "sPageButtonActive": "active",
                dom: 'lr<"toolbar">tip',
                stateSave: true,
                "stateDuration": 60 * 60 * 24 * 7,
                deferRender: true,
                "lengthChange": true,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 50,
                "bFilter": true,
                "bInfo": true,
                "searching": true,
                "order": [[0, "DESC"]],
                columns: [
                    {"data": 'id', order: true},
                    {"data": 'order_id'},
                    {"data": 'gateway.name'},
                    {"data": 'amount'},
                    {"data": 'status'},
                    {"data": 'created_at'},
                    {"data": 'action', searching: false, sortable: false}
                ]
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
                "</div>" +
                "<div class='btn-group pt pr pb pl'>" +
                "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' data-bs-auto-close='outside'><i class='fa fa-filter'></i>" +
                "<span class='caret'></span></button>" +
                "<div class='dropdown-menu dropdown-menu-right' style='padding: 10px;'>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='gatewayId'></select>" +
                "</div>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='status'>" +
                "<option value=''>All</option>" +
                "<option value='pending'>Pending</option>" +
                "<option value='processing'>Processing</option>" +
                "<option value='failed'>Failed</option>" +
                "<option value='cancelled'>Cancelled</option>" +
                "<option value='declined'>Declined</option>" +
                "<option value='success'>Success</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

            jQuery('table').on('click', '.lifeCycle', function () {
                $(modal).find('.modal-dialog').addClass('modal-xl');
                $(modal).find('.modal-body').html("");
                $(modal).find('.modal-footer').hide();
                let url = $(this).data('action');
                $.ajax({
                    type: "GET",
                    url: url,
                    data: null,
                    success: function (response, textStatus, xhr) {
                        console.log(response);
                        if (response.code === 200) {
                            let transaction = response.data;
                            $(modal).find('.modal-body').html('<table class="table table-bordered"><tr><th>APP NAME</th><th>Title</th><th>Request payload</th><th>Response payload</th><th>Status</th><th>Time</th></tr><tbody id="trxBody"><table>');
                            let data = '';
                            $.each(transaction, function (key, item) {
                                data += '<tr>' +
                                    '<td>' + item.app_name + '</td>' +
                                    '<td>' + item.title + '</td>' +
                                    '<td><pre>' + JSON.stringify(item.request_payload, null, 4) + '</pre></td>' +
                                    '<td>' + JSON.stringify(item.response_payload, null, 4) + '</td>' +
                                    '<td>' + item.status + '</td>' +
                                    '<td>' + item.time + '</td>' +
                                    '</tr>';
                            });
                            $(modal).find('tbody#trxBody').append(data);
                            $(modal).modal('show');
                        }
                    }
                });
                return false;
            });

            $("#filterPanel #gatewayId").select2({
                allowClear: true,
                width: "100%",
                placeholder: "Select gateway",
                delay: 250,
                ajax: {
                    url: '{{ route('gateway.suggestion') }}',
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

            $("#filterPanel input").on("keyup", function () {
                table.draw();
            });
            $("#filterPanel select").on("change", function () {
                table.draw();
            });
        });
    </script>
@endsection

