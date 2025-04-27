<div class="card">
    <div class="card-body">
        <form class="form form-horizontal" action="{{ route('setting.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="general">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="login_insist_title">Login Insist Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <input type="text" id="login_insist_title" class="form-control" value="{{ old('login_insist_title', getOption('login_insist_title')) }}" name="login_insist_title" placeholder="Login insist title" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="login_insist_description">Login Insist Description</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <textarea id="login_insist_description" class="form-control" name="login_insist_description" placeholder="Login insist description">{{ old('login_insist_description', getOption('login_insist_description')) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="company_name">Company name</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" id="company_name" class="form-control" value="{{ old('company_name', getOption('company_name')) }}" name="company_name" placeholder="Company name" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="company_email">Company Email</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input type="email" id="company_email" class="form-control" value="{{ old('company_email', getOption('company_email')) }}" name="company_email" placeholder="Email address" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="contact-icon">Company Mobile</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                </div>
                                <input type="text" id="contact-icon" class="form-control" name="company_mobile" value="{{ old('company_mobile', getOption('company_mobile')) }}" placeholder="Mobile" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="contact-icon">Whatsapp Number</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-whatsapp"></i></span>
                                </div>
                                <input type="text" id="contact-icon" class="form-control" name="company_whatsapp" value="{{ old('company_whatsapp', getOption('company_whatsapp')) }}" placeholder="Whatsapp" />
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
