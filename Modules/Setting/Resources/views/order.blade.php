<div class="card">
    <div class="card-body">
        <form class="form form-horizontal" action="{{ route('setting.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="order">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="checkout_terms_and_condition">Checkout terms and conditions</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-th"></i></span>
                                </div>
                                <textarea class="form-control" name="checkout_terms_and_condition">{{ old('checkout_terms_and_condition', getOption('checkout_terms_and_condition')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="checkout_terms_of_use">Terms of use</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-th"></i></span>
                                </div>
                                <textarea class="form-control" name="checkout_terms_of_use">{{ old('checkout_terms_of_use', getOption('checkout_terms_of_use')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                        </div>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
