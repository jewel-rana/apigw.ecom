@if(\App\Helpers\CommonHelper::hasPermission(['purchase-list', 'purchase-create', 'purchase-update', 'purchase-action']))
    <li class="@if(in_array($current_path, ['dashboard/purchase', 'dashboard/purchase'])) active @endif">
        <a href="{{ route('purchase.index') }}">
            <i class="fa fa-cubes"></i>
            <span class="link-title">Purchase Order</span>
        </a>
    </li>
@endif
