<div class="card">
    <div class="card-body">
        <form class="form form-horizontal" action="{{ route('setting.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="header">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row uploadPrent">
                        <div class="col-sm-3 col-form-label">
                            <label for="logo">Logo</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" id="logo" class="form-control" value="{{ old('logo', getOption('logo')) }}" name="logo" placeholder="Logo" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary jQFileUpload" role="logo">
                                        <span class="fa fa-upload"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section2_menu_id">Header Menu (Main)</label><br />
                            <small>Header main menu</small>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="header_top_menu" class="form-control" required>
                                    <option value="">Select menu</option>
                                    @foreach(app(\Modules\Menu\MenuService::class)->all() as $menu)
                                        <option value="{{ strtolower($menu->name) }}"
                                                @if(old('header_top_menu', getOption('header_top_menu')) == strtolower($menu->name)) selected @endif>{{ $menu->name }} ({{ $menu->description }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section2_menu_id">Explore Menu</label><br />
                            <small>Header explore menus (Categories & Services under explore menu in dropdown)</small>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="header_explore_menu" class="form-control" required>
                                    <option value="">Select menu</option>
                                    @foreach(app(\Modules\Menu\MenuService::class)->all() as $menu)
                                        <option value="{{ strtolower($menu->name) }}"
                                                @if(old('header_explore_menu', getOption('header_explore_menu')) == strtolower($menu->name)) selected @endif>{{ $menu->name }} ({{ $menu->description }})</option>
                                    @endforeach
                                </select>
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
