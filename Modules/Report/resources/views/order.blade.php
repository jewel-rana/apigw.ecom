@extends('metis::layouts.master')

@section('header')
    <!-- daterange picker -->
    <link rel="stylesheet" href="/lib/daterangepicker/daterangepicker.css">
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3>{{ $title ?? '' }}</h3>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation"
                            class="@if(!request()->has('tab') || request()->input('tab') == 'info') active @endif">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel"
                             class="tab-pane fade active in"
                             id="home">
                            <div class="row">
                                <div class="col-md-7">
                                    <form action="{{ route('report.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="order">
                                        <input type="hidden" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" id="startDate">
                                        <input type="hidden" name="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d')) }}" id="endDate">
                                        <div class="form-group">
                                            <label>Period of time</label>
                                            <button type="button" class="form-control btn btn-default float-left ml-0" id="reportrange">
                                                <i class="far fa-calendar-alt"></i>
                                                <span>{{ now()->format('d F, Y') }} - {{ now()->format('d F, Y') }}</span>
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <label>Operator</label>
                                            <select class="form-control" name="operator_id" id="operatorId"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Bundle</label>
                                            <select class="form-control" name="bundle_id" id="bundleId"></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="status" id="statusId">
                                                <option value="">All</option>
                                                <option value="pending">Pending</option>
                                                <option value="failed">Failed</option>
                                                <option value="complete">Completed</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label></label>
                                            <button class="btn btn-primary" type="submit">Export</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <!-- date-range-picker -->
    <script src="/lib/moment/moment.min.js"></script>
    <script src="/lib/daterangepicker/daterangepicker.js"></script>
<script>
    jQuery(function ($) {
        $("#operatorId").select2({
            allowClear: true,
            width: "element",
            placeholder: "Select operator",
            delay: 250,
            ajax: {
                url: '{{ route('operator.suggestion') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: params.term
                    }
                },
                results: function (data, page) {
                    return {results: data.data};
                }
            }
        });

        $("#bundleId").select2({
            allowClear: true,
            width: "element",
            placeholder: "Select bundle",
            delay: 250,
            ajax: {
                url: '{{ route('bundle.suggestion') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: params.term,
                        operator_id: $('#operatorId').val()
                    }
                },
                results: function (data, page) {
                    return {results: data.data};
                }
            }
        });

        $("#gatewayId").select2({
            allowClear: true,
            width: "element",
            placeholder: "Select gateway",
            delay: 250,
            ajax: {
                url: '{{ route('gateway.suggestion') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: params.term
                    }
                },
                results: function (data, page) {
                    return {results: data.data};
                }
            }
        });

        //Date range as a button
        $('#reportrange').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#reportrange span').html(start.format('DD MMMM, YYYY') + ' - ' + end.format('DD MMMM, YYYY'));
                $('#startDate').val(start.format('YYYY-MM-DD'));
                $('#endDate').val(end.format('YYYY-MM-DD'));
            }
        )
    });
</script>
@endsection
