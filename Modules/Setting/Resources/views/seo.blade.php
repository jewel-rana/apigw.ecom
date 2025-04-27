<div class="card">
    <div class="card-body">
        <form class="form form-horizontal" action="{{ route('setting.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="seo">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="meta_title">Meta Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <input type="text" id="meta_title" class="form-control" value="{{ old('meta_title', getOption('meta_title')) }}" name="meta_title" placeholder="Meta title" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="meta_description">Meta Description</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <textarea type="text" id="meta_description" class="form-control" name="meta_description" placeholder="Meta description">{{ old('meta_description', getOption('meta_description')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="meta_keyword">Meta Keywords</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <textarea type="text" id="meta_keyword" class="form-control" name="meta_keyword" placeholder="Meta keyword">{{ old('meta_keyword', getOption('meta_keyword')) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="og_title">OG:Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <input type="text" id="og_title" class="form-control" value="{{ old('og_title', getOption('og_title')) }}" name="og_title" placeholder="OG:title" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="og_description">OG:Description</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                </div>
                                <textarea type="text" id="og_description" class="form-control" name="og_description" placeholder="OG:description">{{ old('og_description', getOption('og_description')) }}</textarea>
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
