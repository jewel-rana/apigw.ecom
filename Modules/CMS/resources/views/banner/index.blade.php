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
                                <h5>{{ $title ?? 'Banners' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        @if (\App\Helpers\CommonHelper::hasPermission(['banner-create']))
                                            <a href="{{ route('banner.create') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle"></i> Add new banner
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
                                        <th>Name</th>
                                        <th>Label</th>
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
                    url: '{{ route('banner.index') }}'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'label', name: 'label'},
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
