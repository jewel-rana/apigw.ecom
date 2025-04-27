<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-striped" id="attributeTables">
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Language</th>
                        <th>Value</th>
                        <th><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-3">
                <form class="form form-horizontal" action="{{ route('setting.attribute.store') }}" method="POST"
                      id="settingAttributeForm">
                    @csrf
                    <div class="form-group">
                        <label for="login_insist_title">Language (*)</label>
                        <select id="attributeLang" class="form-control" name="lang">
                            @foreach(app(\Modules\Region\Services\LanguageService::class)->all()->where('code', '!=', 'en') as $language)
                                <option value="{{ $language->code }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attributeKey">Key (*)</label>
                        <select id="attributeKey" style="width: 100%" class="form-control" name="key"></select>
                    </div>
                    <div class="form-group">
                        <label for="attributeValue">Value (*)</label>
                        <textarea id="attributeValue" class="form-control" rows="5" name="value"></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                        </div>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
