<form class="form form-horizontal" action="{{ route('setting.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 1 (Banner)</h4>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section1_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section1_enabled">
                                    <option value="yes" @if(old('section1_enabled', getOption('section1_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section1_enabled', getOption('section1_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="main_slider">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section1_title"
                                       value="{{ old('section1_title', getOption('section1_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="main_slider">Banner</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section1_banner_id" class="form-control" required>
                                    <option value="">Select banner</option>
                                    @foreach(app(\Modules\CMS\App\Services\BannerService::class)->all() as $banner)
                                        <option value="{{ $banner->id }}"
                                                @if(old('section1_banner_id', getOption('section1_banner_id')) == $banner->id) selected @endif>{{ $banner->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 2 (Services)</h4>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section2_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section2_enabled">
                                    <option value="yes" @if(old('section2_enabled', getOption('section2_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section2_enabled', getOption('section2_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section2_menu_id">Menu</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section2_menu_id" class="form-control" required>
                                    <option value="">Select menu</option>
                                    @foreach(app(\Modules\Menu\MenuService::class)->all() as $menu)
                                        <option value="{{ strtolower($menu->name) }}"
                                                @if(old('section2_menu_id', getOption('section2_menu_id')) == strtolower($menu->name)) selected @endif>{{ $menu->name }} ({{ $menu->description }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 3 (Game Categories - Menu) </h4><hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section3_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section3_enabled">
                                    <option value="yes" @if(old('section3_enabled', getOption('section3_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section3_enabled', getOption('section3_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section3_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section3_title"
                                       value="{{ old('section3_title', getOption('section3_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section3_description">Menu</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section3_menu_id" class="form-control" required>
                                    <option value="">Select menu</option>
                                    @foreach(app(\Modules\Menu\MenuService::class)->all() as $menu)
                                        <option value="{{ strtolower($menu->name) }}"
                                                @if(old('section3_menu_id', getOption('section3_menu_id')) == strtolower($menu->name)) selected @endif>{{ $menu->name }} ({{ $menu->description }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 4 (Gift Cards)</h4>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section4_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section4_enabled">
                                    <option value="yes" @if(old('section4_enabled', getOption('section4_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section4_enabled', getOption('section4_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section4_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section4_title"
                                       value="{{ old('section4_title', getOption('section4_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section4_service_type_id">Choose Services</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section4_service_type_id" class="form-control" required>
                                    <option value="">Select services</option>
                                    @foreach(app(\Modules\ServiceType\Services\ServiceTypes::class)->all() as $service)
                                        <option value="{{ $service->id }}"
                                                @if(old('section4_service_type_id', getOption('section4_service_type_id')) == $service->id) selected @endif>{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section4_item_type">Item type</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section4_item_type"
                                        class="form-control">
                                    <option value="operator" @if(old('section4_item_type', getOption('section4_item_type', 'bundle')) == 'operator') selected @endif>Operator</option>
                                    <option value="bundle" @if(old('section4_item_type', getOption('section4_item_type', 'bundle')) == 'bundle') selected @endif>Bundles (Product)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section4_title">Item Showing</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="number" name="section4_item_limit"
                                       value="{{ old('section4_item_limit', getOption('section4_item_limit', 5)) }}"
                                       class="form-control" placeholder="5">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 5 (Offer banner)</h4>
            <small>Offer banner section</small>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section5_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section5_enabled">
                                    <option value="yes" @if(old('section5_enabled', getOption('section5_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section5_enabled', getOption('section5_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section5_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section5_title"
                                       value="{{ old('section5_title', getOption('section5_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="main_slider">Banner</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section5_banner_id" class="form-control" required>
                                    <option value="">Select banner</option>
                                    @foreach(app(\Modules\CMS\App\Services\BannerService::class)->all() as $banner)
                                        <option value="{{ $banner->id }}"
                                                @if(old('section5_banner_id', getOption('section5_banner_id')) == $banner->id) selected @endif>{{ $banner->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 6 (Game top up)</h4>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section6_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section6_enabled">
                                    <option value="yes" @if(old('section6_enabled', getOption('section6_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section6_enabled', getOption('section6_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section6_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section6_title"
                                       value="{{ old('section6_title', getOption('section6_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section6_service_type_id">Choose Services</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section6_service_type_id" class="form-control" required>
                                    <option value="">Select services</option>
                                    @foreach(app(\Modules\ServiceType\Services\ServiceTypes::class)->all() as $service)
                                        <option value="{{ $service->id }}"
                                                @if(old('section6_service_type_id', getOption('section6_service_type_id')) == $service->id) selected @endif>{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section6_item_type">Item type</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section6_item_type"
                                        class="form-control">
                                    <option value="operator" @if(old('section6_item_type', getOption('section6_item_type', 'bundle')) == 'operator') selected @endif>Operator</option>
                                    <option value="bundle" @if(old('section6_item_type', getOption('section6_item_type', 'bundle')) == 'bundle') selected @endif>Bundles (Product)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section6_item_limit">Item Showing</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="number" name="section6_item_limit"
                                       value="{{ old('section6_item_limit', getOption('section6_item_limit', 5)) }}"
                                       class="form-control" placeholder="5">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 7 (Deal of the day - Banner)</h4>
            <small>Deal of the day banner</small>
            <hr/>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section7_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section7_enabled">
                                    <option value="yes" @if(old('section7_enabled', getOption('section7_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section7_enabled', getOption('section7_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section7_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section7_title"
                                       value="{{ old('section7_title', getOption('section7_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section7_title">Banner</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section7_banner_id">
                                    <option value="">Choose banner</option>
                                    @foreach(app(\Modules\CMS\App\Services\BannerService::class)->all() as $banner)
                                        <option value="{{ $banner->id }}"
                                                @if(old('section7_banner_id', getOption('section7_banner_id')) == $banner->id) selected @endif>{{ $banner->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 8</h4>
            <hr/>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section8_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section8_enabled">
                                    <option value="yes" @if(old('section8_enabled', getOption('section8_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section8_enabled', getOption('section8_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section8_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section8_title"
                                       value="{{ old('section8_title', getOption('section8_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section8_title">Description</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <textarea type="text" name="section8_description" class="form-control"
                                          placeholder="Description">{{ old('section8_description', getOption('section8_description')) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section8_category_id">Category</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section8_category_id">
                                    <option value="">Choose category</option>
                                    @foreach(app(\Modules\Category\App\Services\CategoryService::class)->all() as $category)
                                        <option value="{{ $category->id }}"
                                                @if(old('section8_category_id', getOption('section8_category_id')) == $category->id) selected @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section8_item_type">Item type</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section8_item_type"
                                        class="form-control">
                                    <option value="operator" @if(old('section8_item_type', getOption('section8_item_type', 'bundle')) == 'operator') selected @endif>Operator</option>
                                    <option value="bundle" @if(old('section8_item_type', getOption('section8_item_type', 'bundle')) == 'bundle') selected @endif>Bundles (Product)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section8_item_limit">Item Limit</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section8_item_limit"
                                       value="{{ old('section8_item_limit', getOption('section8_item_limit', 5)) }}"
                                       class="form-control" placeholder="Item limit">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 9 (New Arrivals)</h4>
            <small>New arrivals banner</small>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section9_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section9_enabled">
                                    <option value="yes" @if(old('section9_enabled', getOption('section9_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section9_enabled', getOption('section9_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section7_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section9_title"
                                       value="{{ old('section9_title', getOption('section9_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="main_slider">Banner</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select name="section9_banner_id" class="form-control" required>
                                    <option value="">Select banner</option>
                                    @foreach(app(\Modules\CMS\App\Services\BannerService::class)->all() as $banner)
                                        <option value="{{ $banner->id }}"
                                                @if(old('section9_banner_id', getOption('section9_banner_id')) == $banner->id) selected @endif>{{ $banner->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 10 (Recommendations)</h4>
            <small>Recommendation for you</small>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section10_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section10_enabled">
                                    <option value="yes" @if(old('section10_enabled', getOption('section10_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section10_enabled', getOption('section10_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section10_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section10_title"
                                       value="{{ old('section10_title', getOption('section10_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="recommendation_item_limit">Item Limit</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="recommendation_item_limit"
                                       value="{{ old('recommendation_item_limit', getOption('recommendation_item_limit', 5)) }}"
                                       class="form-control" placeholder="Item limit">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 11</h4>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section11_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section11_enabled">
                                    <option value="yes" @if(old('section11_enabled', getOption('section11_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section11_enabled', getOption('section11_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section11_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section11_title"
                                       value="{{ old('section11_title', getOption('section11_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Section 12 (Become a member)</h4>
            <hr/>
        </div>
        <div class="card-body">
            <input type="hidden" name="tab" value="home">
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section12_title">Enable section?</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <select class="form-control" name="section12_enabled">
                                    <option value="yes" @if(old('section12_enabled', getOption('section12_enabled')) == 'yes') selected @endif>Yes</option>
                                    <option value="no" @if(old('section12_enabled', getOption('section12_enabled')) == 'no') selected @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section12_title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section12_title"
                                       value="{{ old('section12_title', getOption('section12_title')) }}"
                                       class="form-control" placeholder="Title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 col-form-label">
                            <label for="section12_description">Description</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group input-group-merge">
                                <input type="text" name="section12_description"
                                       value="{{ old('section12_description', getOption('section12_description')) }}"
                                       class="form-control" placeholder="Description">
                            </div>
                        </div>
                    </div>
                </div>
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
</form>
