@extends('metis::layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/lib/select2/select2-bootstrap.css">
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Add new country'}}</h3>
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
                                    <form action="{{ route('country.update', $country->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group @error('zone_id') has-error @enderror">
                                            <label for="">Zone (*)</label>
                                            <select name="zone_id"
                                                    class="form-control form-control-lg" id="zoneId"
                                                    required>
                                                <option value="">Select zone</option>
                                                @foreach($zones as $zone)
                                                    <option value="{{ $zone->id }}" @if(old('zone_id', $country->zone_id) == $zone->id) selected @endif>{{ $zone->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
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
                                                    <option value="{{ $timezone->id }}" @if(old('time_zone_id', $country->time_zone_id) == $timezone->id) selected @endif>{{ $timezone->name }}</option>
                                                @endif
                                            </select>
                                            @error('time_zone_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('currency_id') has-error @enderror">
                                            <label for="">Currency (*)</label>
                                            <select name="currency_id"
                                                    class="form-control form-control-lg" id="timeZoneId"
                                                    required>
                                                <option value="">Select currency</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}" @if(old('currency_id', $country->currency_id) == $currency->id) selected @endif>{{ $currency->name }} ({{ $currency->code }})</option>
                                                @endforeach
                                            </select>
                                            @error('currency_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('key') has-error @enderror">
                                            <label for="">Name (*)</label>
                                            <input type="text" name="name" value="{{ old('name', $country->name) }}"
                                                   class="form-control form-control-lg" id="" placeholder="Name of country"
                                                   required>
                                            @error('name')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group @error('code') has-error @enderror">
                                            <label for="">Code (*)</label>
                                            <input type="text" name="code" value="{{ old('code', $country->code) }}" class="form-control" placeholder="Value"
                                                   required>
                                            @error('code')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">ADD</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                            zone_id: $('#zoneId').val()
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
