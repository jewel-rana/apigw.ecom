@extends('metis::layouts.master')

@section('header')
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
                                <h5>{{ $title ?? 'Administrators' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        @if(\App\Helpers\CommonHelper::hasPermission(['user-create']))
                                            <a href="{{ route('user.create') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle"></i> Add new administer
                                            </a>
                                        @endif
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table id="dataTable"
                                       class="table table-bordered table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th class="table-ids">ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
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
                responsive: true,
                ajax: {
                    url: '{{ route('user.index') }}',
                    data: function (data) {
                        data.role_id = $('#filterPanel #roleId').val();
                        data.keyword = $('#filterPanel #keywords').val();
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
                    {"data": 'name'},
                    {"data": 'email'},
                    {"data": 'role', searching: false, sortable: false},
                    {"data": 'status'},
                    {"data": 'actions', searching: false, sortable: false}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });
            document.querySelector('div#dataTable_wrapper .toolbar').innerHTML = "<div class='form-inline' id='filterPanel'>" +
                "<div class='form-group mx-sm-3 mr'>" +
                "<input type='text' class='form-control' id='keywords' placeholder='Keywords...'>" +
                "</div>" +
                "<div class='btn-group pt pr pb pl'>" +
                "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' data-bs-auto-close='outside'><i class='fa fa-filter'></i>" +
                "<span class='caret'></span></button>" +
                "<div class='dropdown-menu dropdown-menu-right' style='padding: 10px;'>" +
                "<div class=''>" +
                "<select class='form-control' id='roleId'></select>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

            $("#filterPanel #roleId").select2({
                allowClear: true,
                width: "100%",
                placeholder: "Select role",
                delay: 250,
                ajax: {
                    url: '{{ route('role.suggestion') }}',
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

