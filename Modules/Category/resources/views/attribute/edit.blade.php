@extends('metis::layouts.master')

@section('header')
    <link rel="stylesheet" href="/assets/libs/jquery-tag-input/dist/jquery.tagsinput-revisited.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Update attribute'}}</h3>
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
                                    <form action="{{ route('category.attribute.update', $attribute->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="category_id"
                                               value="{{ old('category_id', $attribute->category_id) }}">
                                        <div class="form-group @error('lang') has-error @enderror">
                                            <label for="">Language (*)</label>
                                            <select name="lang" class="form-control">
                                                <option value="">Select language</option>
                                                @foreach($languages as $language)
                                                    <option value="{{ $language->code }}"
                                                            @if(old('lang', $attribute->lang) == $language->code) selected @endif>
                                                        {{ $language->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('lang')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('key') has-error @enderror">
                                            <label for="">Key (*)</label>
                                            <input type="text" name="key" value="{{ old('key', $attribute->key) }}"
                                                   class="form-control form-control-lg jquery-input-tags" id="" placeholder="Key"
                                                   required>
                                            @error('key')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('value') has-error @enderror">
                                            <label for="">Value (*)</label>
                                            <textarea name="value" class="form-control" placeholder="Value"
                                                      required>{{ old('value', $attribute->value) }}</textarea>
                                            @error('label')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
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
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/libs/jquery-tag-input/dist/jquery.tagsinput-revisited.min.js') }}"></script>
    <script>
        jQuery(function($) {
            $('.jquery-input-tags').tagsInput({

                // min/max number of characters
                minChars: 0,
                maxChars: null,

                // max number of tags
                limit: 1,

                // custom placeholder
                placeholder: 'Key',

                // RegExp
                validationPattern: null,

                // duplicate validation
                unique: true,
                'autocomplete': {
                    source: {!! json_encode(\Modules\Category\App\Models\Category::$defaultKeys) !!}
                }
            });
        });
    </script>
@endsection
