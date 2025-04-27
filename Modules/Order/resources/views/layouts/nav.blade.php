@if(\App\Helpers\CommonHelper::hasPermission(['order-list', 'order-show', 'order-action', 'payment-list', 'payment-show', 'payment-action']))
    <li class="@if(in_array($module_name, ['order', 'payment'])) active @endif">
        <a href="javascript:;"
           @if(in_array($module_name, ['order', 'payment'])) area-expanded="true" @endif>
            <i class="fa fa-shopping-cart"></i>
            <span class="link-title">Orders</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="collapse">
            @if(\App\Helpers\CommonHelper::hasPermission(['order-list', 'order-show', 'order-action']))
                <li class="@if($current_route == 'order.index') active @endif">
                    <a href="{{ route('order.index') }}">
                        <i class="fa fa-shopping-bag"></i>&nbsp;Orders</a>
                </li>
            @endif
            @if(\App\Helpers\CommonHelper::hasPermission(['payment-list', 'payment-show', 'payment-action']))
                <li class="@if($current_class == 'PaymentController') active @endif">
                    <a href="{{ route('payment.index') }}">
                        <i class="fa fa-money"></i> Payments</a>
                </li>
            @endif
            @if(\App\Helpers\CommonHelper::hasPermission(['order-product-list']))
                <li class="@if($current_route == 'order.sold') active @endif">
                    <a href="{{ route('order.sold') }}">
                        <i class="fa fa-money"></i> Sold products</a>
                </li>
            @endif
            @if(\App\Helpers\CommonHelper::hasPermission(['refund-list', 'refund-show', 'refund-action']))
                <li class="@if($current_class == 'OrderRefundController') active @endif">
                    <a href="{{ route('refund.index') }}">
                        <i class="fa fa-exchange"></i> Refunds</a>
                </li>
            @endif
        </ul>
    </li>
@endif
