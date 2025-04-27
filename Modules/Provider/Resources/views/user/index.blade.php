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
                                <h5>Providers</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        <a href="{{ route('provider.user.create') }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus-circle"></i> Add new user
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
    <script src="{{ asset('lib/DataTables/datatables.js') }}"></script>
    <script>
        $(function () {
            $('#dataTable').DataTable({
                "sAjaxSource": "{{ route('provider.user.index') }}",
                "bAutoWidth": false,
                "sPageButtonActive": "active", dom: 'lBrtip',
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
                    {"data": 'status'},
                    {"data": 'actions'}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });
        });
    </script>
@endsection
