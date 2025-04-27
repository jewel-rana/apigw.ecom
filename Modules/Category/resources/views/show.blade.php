@extends('metis::layouts.master')

@section('header')

@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>{{ $title ?? 'Show Category'}}
                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-default pull-right"><i class="fa fa-edit"></i> Edit</a>
                </h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation" class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true">Info</a>
                        </li>
                        <li role="presentation" class="@if(request()->has('tab') && request()->input('tab') == 'attribute') active @endif">
                            <a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">Attributes</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade @if(!request()->has('tab') || request()->input('tab') == 'info') active in @endif" id="home">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td>{{ $category->code }}</td>
                                </tr>
                                <tr>
                                    <th>Icon</th>
                                    <td>
                                        <img src="{{ $category->media_attachment_url }}" style="width: 80px" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $category->nice_status }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>{{ $category->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Last updated at</th>
                                    <td>{{ $category->updated_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane fade @if(request()->has('tab') && request()->input('tab') == 'attribute') active in @endif" id="attributes">
                            <div class="pull-right">
                                <a href="{{ route('category.attribute.create', ['category_id' => $category->id]) }}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add new</a>
                            </div>
                            <div class="clearfix"></div>
                            <table class="table table-striped" id="attributesTable">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Language</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th><i class="fa fa-cogs"></i></th>
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
            let table = $('#attributesTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('category.attribute.index') }}",
                    pages: 5, // number of pages to cache
                    'data': function (data) {
                        // Read values
                        data.category_id = "{{ $category->id }}";
                    }
                },
                "bAutoWidth": false,
                "sPageButtonActive": "active", dom: 'lrtip',
                "lengthChange": false,
                lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
                "pageLength": 25,
                "bFilter": false,
                "bInfo": false,
                "searching": false,
                "order": [[0, "desc"]],
                columns: [
                    {"data": 'id'},
                    {"data": 'lang'},
                    {"data": 'key'},
                    {"data": 'value'},
                    {"data": 'actions'}
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

            //check all item
            $('#checkedAll').on("click", function (e) {
                e.defaultPrevented;
                let parent = $(this).parents("#settings");
                if ($(this).is(":checked")) {
                    $(parent).find("input.checkItem").prop('checked', "checked");
                } else {
                    $(parent).find("input.checkItem").each(function () {
                        $(this).prop('checked', false);
                    });
                }
            });
        });
    </script>
@endsection
