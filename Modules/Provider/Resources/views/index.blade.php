@extends('metis::layouts.master')

@section('header')
    <link href="{{ asset('lib/DataTables/datatables.css') }}">
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
                                <h5>Suppliers</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        <a href="{{ route('provider.create') }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus-circle"></i> Add new Supplier
                                        </a>
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table id="dataTable"
                                       class="table table-bordered table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Balance (IQD)</th>
                                        <th>Last deposit</th>
                                        <th>Status</th>
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
    <script>
        $(function () {
            let table = $('#dataTable').DataTable({
                serverSide: true,
                processing: true,
                "ajax":  {
                    url: "{{ route('provider.index') }}",
                    data: function (data) {
                        data.status = $('#filterPanel #status').val();
                        data.keyword = $('#filterPanel #keywords').val();
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
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'id'},
                    {"data": 'name'},
                    {"data": 'email'},
                    {"data": 'balance'},
                    {"data": 'last_deposit'},
                    {"data": 'status'},
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
                "<input type='text' class='form-control' id='keywords' placeholder='Search...'>" +
                "</div>" +
                "<div class='btn-group pt pr pb pl'>" +
                "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' data-bs-auto-close='outside'><i class='fa fa-filter'></i>" +
                "<span class='caret'></span></button>" +
                "<div class='dropdown-menu dropdown-menu-right' style='padding: 10px;'>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='operatorId'></select>" +
                "</div>" +
                "<div class='panel-row'>" +
                "<select class='form-control' id='status'>" +
                "<option value=''>All</option>" +
                "<option value='0'>Inactive</option>" +
                "<option value='1'>Active</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

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
                            term: params.term
                        }
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            });

            $("#filterPanel input").on("keyup", function () {
                table.draw();
            });
            $("#filterPanel select").on("change", function () {
                table.draw();
            });
        });
    </script>
@endsection
