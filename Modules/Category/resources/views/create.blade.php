@extends('metis::layouts.master')

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light lter">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <header class="dark">
                                <div class="icons"><i class="fa fa-check"></i></div>
                                <h5>{{ $title ?? 'Add new provider' }}</h5>
                                <!-- .toolbar -->
                                <div class="toolbar">
                                    <nav style="padding: 8px;">

                                    </nav>
                                </div>
                            </header>
                            <div id="collapse2" class="body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="form-horizontal" id="popup-validation"
                                      action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Service Type (*)</label>
                                        <div class="col-lg-4">
                                            <select class="validate[required] form-control" name="service_type_id" required>
                                                <option value="">Select service</option>
                                                @foreach($service_types as $service)
                                                    <option value="{{ $service->id }}" @if(old('service_type_id') == $service->id) selected @endif>{{ $service->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Name (*)</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="validate[required] form-control" name="name"
                                                   id="req" value="{{ old('name') }}" placeholder="Category name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Code (Short name) (*)</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="validate[required] form-control" name="code"
                                                   id="req" value="{{ old('code') }}" placeholder="Short name of category">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Color (*)</label>
                                        <div class="col-lg-4">
                                            <select class="validate[required] form-control" name="color" required>
                                                <option value="">Select color</option>
                                                @foreach($colors as $k => $color)
                                                    <option value="{{ $color }}" @if(old('color') == $color) selected @endif>{{ $color }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Icon (*)</label>
                                        <div class="col-lg-4">
                                            <input type="file" name="attachment" class="form-control" placeholder="Choose category Icon">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Parent category</label>
                                        <div class="col-lg-4">
                                            <select class="validate[required] form-control" name="parent_id">
                                                <option value="">Select parent</option>
                                                @foreach($parents as $parent)
                                                    <option value="{{ $parent->id }}" @if(old('parent_id') == $parent->id) selected @endif>{{ $parent->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-actions no-margin-bottom">
                                        <label class="control-label col-lg-4"></label>
                                        <div class="col-lg-4">
                                            <button type="submit" class="btn btn-primary">CREATE</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.inner -->
        </div>
        <!-- /.outer -->
    </div>
    <!-- /#content -->
@endsection
