@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Add new language'}}</h3>
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
                                    <form action="{{ route('language.update', $language->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group @error('key') has-error @enderror">
                                            <label for="">Name (*)</label>
                                            <input type="text" name="name" value="{{ old('name', $language->name) }}"
                                                   class="form-control form-control-lg" id="" placeholder="Name of language"
                                                   required>
                                            @error('name')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('code') has-error @enderror">
                                            <label for="">Code (*)</label>
                                            <input type="text" name="code" value="{{ old('code', $language->code) }}" class="form-control" placeholder="Value"
                                                   required>
                                            @error('code')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('status') has-error @enderror">
                                            <label>Status (*)</label>
                                            <select class="form-control" name="status">
                                                <option value="1" @if(old('status', $language->status) == 1) selected @endif>Active</option>
                                                <option value="0" @if(old('status', $language->status) == 0) selected @endif>Inactive</option>
                                            </select>
                                            @error('status')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="type" value="ltr" @if(old('type', $language->type) == 'ltr') checked @endif> LTR (Left align)
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="type" value="rtl" @if(old('type', $language->type) == 'rtl') checked @endif> RTL (Right align)
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">UPDATE</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
    <script></script>
@endsection
