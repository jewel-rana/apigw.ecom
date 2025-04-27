<div class="card">
    <div class="card-body">
        <form class="form form-horizontal" action="{{ route('setting.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="other">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="internet_recharge_category_id">Internet Recharge Category</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-th"></i></span>
                                </div>
                                <select id="internet_recharge_category_id" class="form-control" name="internet_recharge_category_id" required>
                                    <option value="">Select category</option>
                                    @foreach(app(\Modules\Category\App\Services\CategoryService::class)->all() as $category)
                                        <option value="{{ $category->id }}" @if(old('internet_recharge_category_id', getOption('internet_recharge_category_id')) == $category->id) selected @endif>{{ $category->name }} ({{ $category->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="mobile_recharge_category_id">Mobile Recharge Category</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <div class="input-group-addon">
                                    <span class="input-group-text"><i class="fa fa-th"></i></span>
                                </div>
                                <select id="mobile_recharge_category_id" class="form-control" name="mobile_recharge_category_id" required>
                                    <option value="">Select category</option>
                                    @foreach(app(\Modules\Category\App\Services\CategoryService::class)->all() as $category)
                                        <option value="{{ $category->id }}" @if(old('mobile_recharge_category_id', getOption('mobile_recharge_category_id')) == $category->id) selected @endif>{{ $category->name }} ({{ $category->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="mobile_recharge_category_id">Mobile Recharge Instructions</label>
                            <small>Mobile recharge primary instructions showing in the mobile recharge page.</small>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <textarea name="mobile_recharge_primary_instructions" rows="8" class="form-control editor">{{ old('mobile_recharge_primary_instructions', getOption('mobile_recharge_primary_instructions')) }}</textarea>
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
