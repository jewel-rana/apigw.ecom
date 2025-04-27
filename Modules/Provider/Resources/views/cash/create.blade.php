@extends('metis::layouts.master')

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/lib/select2/select2-bootstrap.css">
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light lter">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <header class="dark">
                                <div class="icons"><i class="fa fa-plus"></i></div>
                                <h5>{{ $title ?? 'Add balance' }}</h5>
                                <!-- .toolbar -->
                                <div class="toolbar">
                                    <nav style="padding: 8px;">

                                    </nav>
                                </div>
                            </header>
                            <div id="collapse2" class="body">
                                <form class="form-horizontal" id="popup-validation"
                                      action="{{ route('provider.cash.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group @error('provider_id') has-error @enderror">
                                        <label for="vendorId" class="control-label col-lg-4">Provider (*)</label>
                                        <div class="col-lg-4">
                                            <select id="vendorId" name="provider_id" class="form-control select2"
                                                    aria-hidden="true" style="width: 100%">
                                                @if($provider != null)
                                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                                @endif
                                            </select>
                                            @error('provider_id0')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4" id="voucherNo">Voucher No.</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="validate[required] form-control"
                                                   name="voucher_number"
                                                   id="voucherNo" value="{{ old('voucher_number') }}"
                                                   placeholder="Voucher No.">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Amount</label>
                                        <div class="col-lg-4">
                                            <input type="number" class="validate[required] form-control"
                                                   name="amount"
                                                   id="amount" value="{{ old('amount') }}" placeholder="Amount">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-lg-4">Currency</label>
                                        <div class="col-lg-4">
                                            <select class="validate[required] form-control" name="currency" id="currency">
                                                <option value="">Select currency</option>
                                                <option>IQD</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-actions no-margin-bottom">
                                        <label class="control-label col-lg-4"></label>
                                        <div class="col-lg-4">
                                            <button type="submit" class="btn btn-primary">ADD</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
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
                placeholder: "Select provider",
                delay: 250,
                ajax: {
                    url: '{{ route('provider.suggestion') }}',
                    dataType: 'json',
                    data: function (params) {
                        let query = {
                            term: params.term,
                            operator_id: $('#operatorId').val()
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
