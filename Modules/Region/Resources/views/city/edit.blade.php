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
                                    <form action="{{ route('city.update', $city->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group @error('country_id') has-error @enderror">
                                            <label for="">Country (*)</label>
                                            <select name="country_id"
                                                    class="form-control form-control-lg" id="countryId"
                                                    required>
                                                <option value="">Select country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" @if(old('country_id', $city->country_id) == $country->id) selected @endif>{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('country_id') has-error @enderror">
                                            <label for="">Timezone (*)</label>
                                            <select name="time_zone_id"
                                                    class="form-control form-control-lg select2" id="timeZoneId"
                                                    required>
                                                <option value="">Select timezone</option>
                                                @if($timezone)
                                                    <option value="{{ $timezone->id }}" @if(old('time_zone_id', $city->time_zone_id) == $timezone->id) selected @endif>{{ $timezone->name }}</option>
                                                @endif
                                            </select>
                                            @error('time_zone_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('key') has-error @enderror">
                                            <label for="">Name (*)</label>
                                            <input type="text" name="name" value="{{ old('name', $city->name) }}"
                                                   class="form-control form-control-lg" id="" placeholder="Name of country"
                                                   required>
                                            @error('name')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('code') has-error @enderror">
                                            <label for="">Code (*)</label>
                                            <input type="text" name="code" value="{{ old('code', $city->code) }}" class="form-control" placeholder="Value"
                                                   required>
                                            @error('code')
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
    <script>
        jQuery(function ($) {
            $(".select2").select2({
                allowClear: true,
                width: "element",
                placeholder: "Select a timezone",
                delay: 250,
                ajax: {
                    url: '{{ route('timezone.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        let query = {
                            term: params.term,
                            country_id: $('#countryId').val()
                        }
                        return query;
                    },
                    results: function (data, page) {
                        return {results: data.data};
                    }
                }
            })
        });
    </script>
@endsection
