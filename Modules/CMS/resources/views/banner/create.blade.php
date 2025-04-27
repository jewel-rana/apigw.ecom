@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Add new banner'}}</h3>
                <div>
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="home">
                            <form action="{{ route('banner.store') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-7">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group @error('name') has-error @enderror">
                                            <label for="">Name (*)</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                   class="form-control form-control-lg" id="" placeholder="ex. main_banner, top_banner, footer_banner"
                                                   required>
                                            <small>No space, separate between underscore (_)</small>
                                            @error('name')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('label') has-error @enderror">
                                            <label for="">Label (*) </label>
                                            <input name="label" class="form-control" id="label" placeholder="Label" value="{{ old('label') }}" required />
                                            <small>Human readable name</small>
                                            @error('label')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">CREATE</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
    <!-- TinyMce Editor -->
    <script src="/js/editor.config.js"></script>
@endsection
