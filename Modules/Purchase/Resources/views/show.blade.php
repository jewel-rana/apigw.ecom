@extends('metis::layouts.master')

@section('header')

@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>{{ $title ?? 'Show Purchase Order'}}
                    <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-default pull-right"><i class="fa fa-edit"></i> Edit</a>
                </h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation" class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">Info</a>
                        </li>
                        <li role="presentation" class="@if(request()->has('tab') && request()->input('tab') == 'items') active @endif">
                            <a href="#items" aria-controls="items" role="tab" data-toggle="tab">Purchase Items</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade @if(!request()->has('tab') || request()->input('tab') == 'info') active in @endif" id="home">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $purchase->id }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>{{ $purchase->provider->name }}</td>
                                </tr>
                                <tr>
                                    <th>Currency</th>
                                    <td>{{ $purchase->currency }}</td>
                                </tr>
                                <tr>
                                    <th>Exchange Rate</th>
                                    <td>{{ $purchase->exchange_rate }}</td>
                                </tr>
                                <tr>
                                    <th>Total Quantity</th>
                                    <td>{{ $purchase->quantity }}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td>{{ $purchase->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $purchase->status }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>{{ $purchase->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Last updated at</th>
                                    <td>{{ $purchase->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'items') active in @endif" id="items">
                            <div class="pull-right">
{{--                                <a href="{{ route('category.attribute.create', ['category_id' => $category->id]) }}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add new</a>--}}
                            </div>
                            <div class="clearfix"></div>
                            <table class="table table-striped" id="itemsTable">
                                <thead>
                                <tr>
                                    <th>Operator</th>
                                    <th>Bundle</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
{{--                                    <th><i class="fa fa-cogs"></i></th>--}}
                                </tr>
                                </thead>
                            </table>
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
    <script src="{{ asset('lib/DataTables/datatables.js') }}"></script>
    <script>

        $(function () {
            let table = $('#itemsTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('purchase.item.index') }}",
                    pages: 5, // number of pages to cache
                    'data': function (data) {
                        // Read values
                        data.purchase_id = "{{ $purchase->id }}";
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active", dom: 'lrtip',
                "lengthChange": false,
                "pageLength": 25,
                "bFilter": false,
                "bInfo": false,
                "searching": false,
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'operator.name'},
                    {"data": 'bundle.name'},
                    {"data": 'quantity'},
                    {"data": 'amount'},
                    // {"data": 'actions', searching: false, sortable: false}
                ],
                "createdRow": function (row, data, index) {
                    // if ( data[6] == 'Disable' ){
                    //     $(row).addClass('highlightError');
                    // }
                }
            });

            $('table').on('click', '.deleteBtn', function() {
                let url = $(this).data('action');
                let type = $(this).data('type');
                $.ajax({
                    type: "delete",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data, status, response)
                    {
                        defaultToast();
                        table.draw();
                    }
                })
                return false;
            });
        });
    </script>
@endsection
