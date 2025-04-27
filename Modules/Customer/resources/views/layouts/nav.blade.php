@if(\App\Helpers\CommonHelper::hasPermission(['customer-list', 'customer-show', 'customer-action']))
    <li class="@if($module_name == 'customer') active @endif">
        <a href="{{ route('customer.index') }}">
            <i class="fa fa-users"></i>
            <span class="link-title">Customers</span>
        </a>
    </li>
@endif
