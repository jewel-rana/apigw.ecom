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
                                <h5>{{ $title ?? 'Customers' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table class="table" id="dataTable">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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

@section('header')

@endsection

@section('footer')
    <!-- date-range-picker -->
    <script src="/lib/moment/moment.min.js"></script>
    <script src="/lib/daterangepicker/daterangepicker.js"></script>
    <script>
        jQuery(function ($) {
            let startDate = "{{ date('Y-m-d') }}";
            let endDate = "{{ date('Y-m-d') }}";
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                dom: 'lr<"toolbar">tip',
                "bAutoWidth": false,
                "sPageButtonActive": "active",
                "lengthChange": true,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 25,
                "bFilter": true,
                "bInfo": true,
                "searching": true,
                "order": [[0, "desc"]],
                ajax: {
                    url: '{{ route('customer.index') }}',
                    data: function (data) {
                        data.status = $('#filterPanel #status').val();
                        data.date_from = startDate;
                        data.date_to = endDate;
                        data.keyword = $('#filterPanel #keywords').val();
                    }
                },
                columns: [
                    {"data": "id", searchable: false},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'mobile'},
                    {data: 'status'},
                    {data: 'created_at'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ]
            });

            document.querySelector('div#dataTable_wrapper .toolbar').innerHTML = "<div class='form-inline' id='filterPanel'>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<input type='text' class='form-control' id='keywords' placeholder='Search...'>" +
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
                "<div class='form-group mx-sm-3 ml'>" +
                "<button class='btn btn-primary' onclick='exportCustomerData()'><i class='fa fa-file-excel-o'></i> Export</button>" +
                "</div>" +
                "<div class='dropdown-menu dropdown-menu-right' style='padding: 10px;'>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='status'>" +
                "<option value=''>All</option>" +
                "<option value='pending'>Pending</option>" +
                "<option value='active'>Active</option>" +
                "<option value='inactive'>Inactive</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

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

            jQuery('table').on('click', '.customerAction', function () {
                let url = $(this).data('url');
                let action = $(this).data('action');
                Swal.fire({
                    title: "Are you sure?",
                    text: "We need your confirmation to do this action.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, do it!"
                }).then((result) => {
                    console.log(result);
                    console.log(url);
                    console.log('action')
                    if (result.value) {
                        $.ajax({
                            type: "PUT",
                            url: url,
                            data: {action: action},
                            success: function (response, textStatus, xhr) {
                                Swal.fire({
                                    title: response.message,
                                    icon: response.status ? "success" : "error"
                                });
                                table.draw();
                            }
                        });
                    }
                });
            });
        });

        function exportCustomerData() {
            let params = {
                status: $('#filterPanel #status').val()
            }
            let queryString = $.param(params);
            window.location.href = '{{ route('customer.export') }}'  + "?" + queryString;
        }
    </script>
@endsection
