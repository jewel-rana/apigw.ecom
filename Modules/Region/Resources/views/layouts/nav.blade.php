@if(\App\Helpers\CommonHelper::hasPermission([
    'country-list', 'country-create', 'country-show', 'country-update',
    'city-list', 'city-create', 'city-update', 'city-action',
    'region-list', 'region-create', 'region-show', 'region-update',
    'language-list', 'language-create', 'language-update'
    ]))
    <li class="@if($module_name == 'region') active @endif">
        <a href="javascript:;" @if($module_name == 'region') area-expanded="true" @endif>
            <i class="fa fa-globe"></i>
            <span class="link-title">Multilingual</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="collapse">
            @if(\App\Helpers\CommonHelper::hasPermission(['country-list', 'country-create', 'country-show', 'country-update']))
                <li class="@if($current_route == 'country.index') active @endif">
                    <a href="{{ route('country.index') }}">
                        <i class="fa fa-list-ul"></i>&nbsp; Countries</a>
                </li>
            @endif
            @if(\App\Helpers\CommonHelper::hasPermission(['city-list', 'city-create', 'city-update', 'city-action']))
                <li class="@if($current_route == 'city.index') active @endif">
                    <a href="{{ route('city.index') }}">
                        <i class="fa fa-list-ul"></i>&nbsp; Cities</a>
                </li>
            @endif
            @if(\App\Helpers\CommonHelper::hasPermission(['region-list', 'region-create', 'region-show', 'region-update']))
                <li class="@if($current_route == 'region.index') active @endif">
                    <a href="{{ route('region.index') }}">
                        <i class="fa fa-list-ul"></i>&nbsp; Regions</a>
                </li>
            @endif
            @if(\App\Helpers\CommonHelper::hasPermission(['language-list', 'language-create', 'language-update']))
                <li class="@if(in_array($current_route, ['language.index'])) active @endif">
                    <a href="{{ route('language.index') }}">
                        <i class="fa fa-language"></i>
                        <span class="link-title">Locals</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
