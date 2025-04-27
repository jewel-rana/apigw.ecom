@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Update banner'}}</h3>
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
                            <form action="{{ route('banner.item.update', [$banner->id, $item->id]) }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
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

                                        <input type="hidden" name="banner_id" value="{{ $banner->id }}">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control"
                                                   value="{{ old('title', $item->pivot->title) }}" placeholder="Title"
                                                   required>
                                        </div>

                                        <div class="form-group">
                                            <label>Slogan</label>
                                            <input name="slogan" value="{{ old('slogan', $item->pivot->slogan) }}"
                                                   class="form-control" placeholder="Slogan">
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <input name="description" value="{{ old('description', $item->pivot->description) }}"
                                                   class="form-control" placeholder="Slogan">
                                        </div>

                                        <div class="form-group">
                                            <label>Text Size</label>
                                            <select name="text_size" class="form-control">
                                                <option value="large"
                                                        @if(old('text_size', $item->pivot->text_size) == 'large') selected @endif>Large
                                                </option>
                                                <option value="medium"
                                                        @if(old('text_size', $item->pivot->text_size) == 'medium') selected @endif>
                                                    Medium
                                                </option>
                                                <option value="small"
                                                        @if(old('text_size', $item->pivot->text_size) == 'small') selected @endif>Small
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Text Color</label>
                                            <input type="text" name="text_color"
                                                   value="{{ old('text_color', $item->pivot->text_color) }}"
                                                   class="form-control" placeholder="Text color">
                                        </div>

                                        <div class="form-group">
                                            <label>Button text</label>
                                            <input type="text" name="btn_text"
                                                   value="{{ old('btn_text', $item->pivot->btn_text) }}"
                                                   class="form-control" placeholder="Button text">
                                        </div>

                                        <div class="form-group">
                                            <label>Button Color</label>
                                            <input type="text" name="btn_color"
                                                   value="{{ old('btn_color', $item->pivot->btn_color) }}"
                                                   class="form-control" placeholder="Button Color">
                                        </div>

                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="text" name="btn_url"
                                                   value="{{ old('btn_url', $item->pivot->btn_url) }}"
                                                   class="form-control" placeholder="URL">
                                        </div>

                                        <div class="form-group">
                                            <label>Upload photo</label>
                                            <input type="file" name="attachment"
                                                   class="form-control-file form-control"/>

                                            <img src="{{ $item->attachment }}" alt="" width="100px">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">UPDATE</button>
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
@endsection
