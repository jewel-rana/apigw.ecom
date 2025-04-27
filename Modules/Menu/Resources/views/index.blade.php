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
                                <h5>{{ $title ?? 'Menus' }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                        @if(\App\Helpers\CommonHelper::hasPermission(['menu-create']))
                                            <a href="{{ route('menu.create') }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus-circle"></i> Add new menu
                                            </a>
                                        @endif
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <table class="table" id="brandTable">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Child count</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($menus as $menu)
                                        <tr>
                                            <td>{{ $menu->name }}</td>
                                            <td>{{ $menu->description }}</td>
                                            <td>{{ $menu->items->count() }}</td>
                                            <td>
                                                @if(\App\Helpers\CommonHelper::hasPermission(['menu-show']))
                                                    <a href="{{ route('menu.show', $menu->id) }}"
                                                       class="btn btn-success"><i class="fa fa-cog"></i> manage</a>
                                                @endif
                                                @if(\App\Helpers\CommonHelper::hasPermission(['menu-update']))
                                                    <a href="{{ route('menu.edit', $menu->id) }}"
                                                       class="btn btn-default">Edit</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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

@section('header')

@endsection
