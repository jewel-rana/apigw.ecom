@extends('metis::layouts.master')

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light lter">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <header>
                                <div class="icons"><i class="fa fa-table"></i></div>
                                <h5>{{ $title ?? 'Medias' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        @if(\App\Helpers\CommonHelper::hasPermission(['media-create']))
                                            <a href="{{ route('media.create') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle"></i> Add new media
                                            </a>
                                        @endif
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table class="table" id="mediaTable">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Thumb</th>
                                        <th>Name</th>
                                        <th>Attachment</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Dimension</th>
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
    @include('media::modal')
@endsection

@section('header')

@endsection

@section('footer')
    <script>
        jQuery(function ($) {
            let table = $('#mediaTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('media.index') }}'
                },
                "order": [[0, "desc"]],
                dom: 'lBrftip',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'thumbnail', name: 'thumbnail'},
                    {data: 'original_name', name: 'original_name'},
                    {data: 'attachment', name: 'attachment', sorting: false, searching: false},
                    {data: 'type', name: 'type'},
                    {data: 'size', name: 'size', searching: false},
                    {data: 'dimension', name: 'dimension'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ]
            });

            $('#search-form').on('submit', function (e) {
                table.draw();
                e.preventDefault();
            });
        });
    </script>
@endsection
