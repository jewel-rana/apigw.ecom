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
                                <h5>{{ $title ?? 'Purchase Order' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        <a href="{{ route('purchase.create') }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus-circle"></i> Add new purchase
                                        </a>
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supplier Name</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Exchange Rate</th>
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
                "sAjaxSource": "{{ route('purchase.index') }}",
                "bAutoWidth": true,
                "sPageButtonActive": "active",
                dom: 'lBfrtip',
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
                    {"data": 'provider.name'},
                    {"data": 'quantity'},
                    {"data": 'amount'},
                    {"data": 'currency'},
                    {"data": 'exchange_rate'},
                    {"data": 'status'},
                    {"data": 'actions', searching: false, sortable: false}
                ]
            });
        });
    </script>
@endsection

