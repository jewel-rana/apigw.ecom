@extends('metis::layouts.master')
@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Add new page'}}</h3>
                <div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="home">
                            <form method="POST" action="{{ route('page.store') }}" enctype="multipart/form-data">
                                @csrf
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
                                                                   value="{{ old('title') }}" placeholder="Page title"
                                                                   required/>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="slug">Slug <span
                                                                    class="error">*</span></label>
                                                            <input type="text" id="slug" class="form-control"
                                                                   name="slug"
                                                                   value="{{ old('slug') }}" placeholder="Page slug"
                                                                   required/>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="email-id-vertical">Description</label>
                                                            <textarea id="email-id-vertical" class="form-control editor"
                                                                      rows="10"
                                                                      name="description"
                                                                      placeholder="Description">{{ old('description') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="invoice-repeater">
                                                    <div data-repeater-list="attribute">
                                                        <h4>Attributes
                                                        <span>
                                                            <button class="btn btn-icon btn-primary btn-xs" type="button"
                                                                    data-repeater-create>
                                                                <i data-feather="plus" class="mr-25"></i>
                                                                <span><i class="fa fa-plus"></i></span>
                                                            </button>
                                                        </span>
                                                        </h4>
                                                        @if(old('attribute'))
                                                            @foreach(old('attribute') as $attribute)
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
                                                                            <div class="form-group pt-5">
                                                                                <label></label>
                                                                                <button
                                                                                    class="btn btn-outline-danger text-nowrap px-1"
                                                                                    data-repeater-delete type="button">
                                                                                    <i data-feather="x"
                                                                                       class="fa fa-times"></i>
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
                                                                        <div class="form-group pt-5">
                                                                            <label></label>
                                                                            <button
                                                                                class="btn btn-danger text-nowrap px-1"
                                                                                data-repeater-delete type="button">
                                                                                <i class="fa fa-times"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr/>

                                                                <div class="form-group">
                                                                    <button type="submit"
                                                                            class="btn btn-success mr-1">{{ __('Create Page') }}</button>
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
    <!-- TinyMce Editor -->
    <script src="/js/editor.config.js"></script>
@endsection
