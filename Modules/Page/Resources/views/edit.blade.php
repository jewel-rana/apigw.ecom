@extends('metis::layouts.master')
@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Update page'}}</h3>
                <div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="home">
                            <form method="POST" action="{{ route('page.update', $page->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
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
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="title">Title <span
                                                                    class="error">*</span></label>
                                                            <input type="text" id="title" class="form-control"
                                                                   name="title"
                                                                   value="{{ old('title', $page->title) }}" placeholder="Page title"
                                                                   required/>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="slug">Slug <span
                                                                    class="error">*</span></label>
                                                            <input type="text" id="slug" class="form-control disabled"
                                                                   name="slug"
                                                                   value="{{ old('slug', $page->slug) }}" placeholder="Page slug"
                                                                   required/>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="email-id-vertical">Description</label>
                                                            <textarea id="email-id-vertical" class="form-control editor"
                                                                      rows="10"
                                                                      name="description"
                                                                      placeholder="Description">{{ old('description', $page->description) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="invoice-repeater">
                                                    <div data-repeater-list="attribute">
                                                        <h4 class="card-title">Attributes
                                                            <span>
                                                            <button class="btn btn-icon btn-primary btn-xs" type="button"
                                                                    data-repeater-create>
                                                                <i data-feather="plus" class="mr-25"></i>
                                                                <span><i class="fa fa-plus"></i></span>
                                                            </button>
                                                        </span>
                                                        </h4>
                                                        @if(old('attribute', $page->attributes))
                                                            @foreach(old('attribute', $page->attributes) as $attribute)
                                                                <div data-repeater-item>
                                                                    <div class="row d-flex align-items-end">
                                                                        <div class="col-md-4 col-12">
                                                                            <div class="form-group">
                                                                                <label for="itemname">Label</label>
                                                                                <input type="text" class="form-control"
                                                                                       name="label"
                                                                                       id="itemname"
                                                                                       value="{{ $attribute['label'] }}"
                                                                                       aria-describedby="itemname"
                                                                                       placeholder="Label"/>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6 col-12">
                                                                            <div class="form-group">
                                                                                <label for="itemvalue">Value</label>
                                                                                <textarea class="form-control"
                                                                                          name="value"
                                                                                          id="itemvalue"
                                                                                          value="{{ $attribute['value'] }}"
                                                                                          aria-describedby="itemvalue"
                                                                                          placeholder="Value"></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-1 col-12 mb-50">
                                                                            <div class="form-group">
                                                                                <button
                                                                                    class="btn btn-outline-danger text-nowrap px-1"
                                                                                    data-repeater-delete type="button">
                                                                                    <i data-feather="x"
                                                                                       class="mr-25"></i>
                                                                                    <span>Delete</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr/>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div data-repeater-item>
                                                                <div class="row d-flex align-items-end">
                                                                    <div class="col-md-4 col-12">
                                                                        <div class="form-group">
                                                                            <label for="itemname">Label</label>
                                                                            <input type="text" class="form-control"
                                                                                   name="label"
                                                                                   id="itemname"
                                                                                   aria-describedby="itemname"
                                                                                   placeholder="Label"/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="itemvalue">Value</label>
                                                                            <textarea class="form-control" name="value"
                                                                                      id="itemvalue"
                                                                                      aria-describedby="itemvalue"
                                                                                      placeholder="Value"></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1 col-12 mb-50">
                                                                        <div class="form-group">
                                                                            <button
                                                                                class="btn btn-outline-danger text-nowrap px-1"
                                                                                data-repeater-delete type="button">
                                                                                <i data-feather="x" class="mr-25"></i>
                                                                                <span>Delete</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr/>

                                                                <div class="form-group">
                                                                    <button type="submit"
                                                                            class="btn btn-success mr-1">{{ __('Update Page') }}</button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <div class="card">
                                            <div class="card-body">
                                            </div>
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
    <script src="{{ asset('assets/plugins/forms/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/forms/form-repeater.js') }}"></script>
    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/26qrb5e7dozqn5fmfkipqjsw740yt9u7g7gu76rngzd2cqhe/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
        tinymce.init({
            selector: 'textarea.editor',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
@endsection
