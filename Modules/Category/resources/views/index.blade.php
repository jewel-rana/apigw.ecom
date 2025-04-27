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
                                <h5>{{ $title ?? 'Categories' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        @if(\App\Helpers\CommonHelper::hasPermission(['category-create']))
                                            <a href="{{ route('category.create') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle"></i> Add new category
                                            </a>
                                        @endif
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table id="dataTable"
                                       class="table table-bordered table-condensed table-hover table-striped table-blue">
                                    <thead>
                                    <tr>
                                        <th class="table-ids">ID</th>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Color</th>
                                        <th>Parent</th>
                                        <th>Service</th>
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
                serverSide: true,
                processing: true,
                responsive: true,
                ajax: {
                    url: '{{ route('category.index') }}',
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active",
                dom: 'lBrftip',
                "lengthChange": true,
                lengthMenu: [[15, 25, 50, 100, 500, -1], [15, 25, 50, 100, 500, "All"]],
                "pageLength": 15,
                "bFilter": true,
                "bInfo": true,
                "searching": true,
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'id'},
                    {"data": 'icon', sortable: false, searchable: false},
                    {"data": 'name'},
                    {"data": 'code'},
                    {"data": 'color', sortable: false, searchable: false},
                    {"data": 'parent', sortable: false, searchable: false},
                    {"data": 'service_type.label', sortable: false, searchable: false},
                    {"data": 'actions', sortable: false, searchable: false}
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
