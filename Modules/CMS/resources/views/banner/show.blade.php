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
                                <h5>{{ $title ?? $banner->label }}</h5>
                                <div class="toolbar">
                                    <nav style="padding: 8px;">
                                    </nav>
                                </div>
                                <!-- /.toolbar -->
                            </header>
                            <div id="collapse4" class="body">
                                <!-- Basic Tables start -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table" id="sliderTable">
                                                    <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Slogan</th>
                                                        <th>Text Size</th>
                                                        <th>Text Color</th>
                                                        <th>Btn Text</th>
                                                        <th>Btn Color</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($banner->medias as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="avatar-group">
                                                                    <img src="{{ asset($item->attachment) }}"
                                                                         class="table-icon" width="60px" height="auto"/>
                                                                </div>
                                                            </td>
                                                            <td>{{ $item->pivot->title }}</td>
                                                            <td>{{ $item->pivot->slogan }}</td>
                                                            <td>{{ $item->pivot->text_size }}</td>
                                                            <td>{{ $item->pivot->text_color }}</td>
                                                            <td>{{ $item->pivot->btn_text }}</td>
                                                            <td>{{ $item->pivot->btn_color }}</td>
                                                            <td>
                                                                <a href="{{ route('banner.item.edit', [$banner->id, $item->id]) }}"
                                                                   class="btn btn-primary" style="float: left"><i class="fa fa-edit"></i></a>
                                                                <form class="form-inline form-horizontal" method="POST"
                                                                      action="{{ route('banner.media.delete', $banner->id) }}">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <input type="hidden" name="media_id"
                                                                           value="{{ $item->id }}">
                                                                    <button type="submit" class="btn btn-danger" title="Delete"
                                                                            onclick="return confirm('Are you sure?')">
                                                                        <i class="fa fa-remove"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        @if (\App\Helpers\CommonHelper::hasPermission(['banner-create']))
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Add new slide</h4>
                                                </div>
                                                <div class="card-body">

                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <form action="{{ route('banner.add', $banner) }}" method="post"
                                                          enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="banner_id" value="{{ $banner->id }}">
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input type="text" name="title" class="form-control"
                                                                   value="{{ old('title') }}" placeholder="Title"
                                                                   required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Slogan</label>
                                                            <input name="slogan" value="{{ old('slogan') }}"
                                                                   class="form-control" placeholder="Slogan">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Description</label>
                                                            <input name="description" value="{{ old('description') }}"
                                                                   class="form-control" placeholder="Slogan">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Text Size</label>
                                                            <select name="text_size" class="form-control">
                                                                <option value="large" @if(old('text_size', 'large') == 'large') selected @endif>Large</option>
                                                                <option value="medium" @if(old('text_size', 'large') == 'medium') selected @endif>Medium</option>
                                                                <option value="small" @if(old('text_size', 'large') == 'small') selected @endif>Small</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Text Color</label>
                                                            <input type="text" name="text_color"
                                                                   value="{{ old('text_color', '#ffffff') }}"
                                                                   class="form-control" placeholder="Text color">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Button text</label>
                                                            <input type="text" name="btn_text"
                                                                   value="{{ old('btn_text') }}"
                                                                   class="form-control" placeholder="Button text">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Button Color</label>
                                                            <input type="text" name="btn_color"
                                                                   value="{{ old('btn_color', '#000000') }}"
                                                                   class="form-control" placeholder="Button Color">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>URL</label>
                                                            <input type="text" name="btn_url"
                                                                   value="{{ old('btn_url') }}"
                                                                   class="form-control" placeholder="URL">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Upload photo</label>
                                                            <input type="file" name="attachment"
                                                                   class="form-control-file form-control" required/>
                                                        </div>

                                                        <div class="form-group">
                                                            <button class="btn btn-primary btn-block"
                                                                    type="submit">{{ __('Add') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- Basic Tables end -->

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

@section('footer')
@endsection
