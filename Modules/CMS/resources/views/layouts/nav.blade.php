@if (\App\Helpers\CommonHelper::hasPermission([
    'banner-list', 'banner-create', 'banner-show', 'banner-update', 'banner-action',
    'menu-list', 'menu-create', 'menu-show', 'menu-update', 'menu-action',
    'page-list', 'page-create', 'page-show', 'page-update', 'page-action',
    'platform-list', 'platform-create', 'platform-update', 'platform-action',
    'device-list', 'device-create', 'device-update', 'device-action'
    ]))
    <li class="@if($module_name == 'cms') active @endif">
        <a href="javascript:;" @if($module_name == 'cms') area-expanded="true" @endif>
            <i class="fa fa-file-text"></i>
            <span class="link-title">CMS</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="collapse">
            @if (\App\Helpers\CommonHelper::hasPermission(['banner-list', 'banner-create', 'banner-show', 'banner-update', 'banner-action']))
                <li class="@if($current_class == 'BannerController') active @endif">
                    <a href="{{ route('banner.index') }}">
                        <i class="fa fa-image"></i>&nbsp;Banners</a>
                </li>
            @endif
            @if (\App\Helpers\CommonHelper::hasPermission(['menu-list', 'menu-create', 'menu-show', 'menu-update', 'menu-action']))
                <li class="@if($current_class == 'MenuController') active @endif">
                    <a href="{{ route('menu.index') }}">
                        <i class="fa fa-list-ul"></i>&nbsp;Menus</a>
                </li>
            @endif
            @if (\App\Helpers\CommonHelper::hasPermission(['page-list', 'page-create', 'page-show', 'page-update', 'page-action']))
                <li class="@if($current_class == 'PageController') active @endif">
                    <a href="{{ route('page.index') }}">
                        <i class="fa fa-leaf"></i>&nbsp;Pages</a>
                </li>
            @endif
            @if (\App\Helpers\CommonHelper::hasPermission(['platform-list', 'platform-create', 'platform-update', 'platform-action']))
                <li class="@if($current_class == 'PlatformController') active @endif">
                    <a href="{{ route('platform.index') }}">
                        <i class="fa fa-leaf"></i>&nbsp;Platforms</a>
                </li>
            @endif
            @if (\App\Helpers\CommonHelper::hasPermission(['device-list', 'device-create', 'device-update', 'device-action']))
                <li class="@if($current_class == 'DeviceController') active @endif">
                    <a href="{{ route('device.index') }}">
                        <i class="fa fa-desktop"></i>&nbsp;Devices</a>
                </li>
            @endif
        </ul>
    </li>
@endif
