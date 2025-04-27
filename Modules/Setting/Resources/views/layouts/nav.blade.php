@if(\App\Helpers\CommonHelper::hasPermission(['setting-manage', 'setting-show']))
    <li class="@if($module_name == 'setting') active @endif">
        <a href="{{ route('setting.index') }}">
            <i class="fa fa-wrench"></i>
            <span class="link-title">Settings</span>
        </a>
    </li>
@endif
