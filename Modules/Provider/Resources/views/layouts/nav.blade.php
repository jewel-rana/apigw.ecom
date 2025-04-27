@if(\App\Helpers\CommonHelper::hasPermission([
    'supplier-list',
    'supplier-create',
    'supplier-show',
    'supplier-update',
    'supplier-action',
    ]))
    <li class="@if($module_name == 'provider') active @endif">
        <a href="javascript:;" @if($module_name == 'provider') area-expanded="true" @endif>
            <i class="fa fa-user-md"></i>
            <span class="link-title">Suppliers</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="collapse">
            <li class="@if($current_route == 'provider.index') active @endif">
                <a href="{{ route('provider.index') }}">
                    <i class="fa fa-list-ul"></i>&nbsp; Supplier lists</a>
            </li>
            @if(\App\Helpers\CommonHelper::hasPermission(['supplier-list',]))
                <li class="@if($current_route == 'provider.cash.index') active @endif">
                    <a href="{{ route('provider.cash.index') }}">
                        <i class="fa fa-list-ul"></i>&nbsp; Deposits</a>
                </li>
            @endif

            @if(\App\Helpers\CommonHelper::hasPermission(['supplier-list',]))
                <li class="@if($current_route == 'provider.user.index') active @endif">
                    <a href="{{ route('provider.user.index') }}">
                        <i class="fa fa-user-secret"></i>&nbsp; Users</a>
                </li>
            @endif
        </ul>
    </li>
@endif
