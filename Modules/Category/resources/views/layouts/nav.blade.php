@if(\App\Helpers\CommonHelper::hasPermission(['category-list', 'category-create', 'category-update', 'category-action']))
    <li class="@if($module_name == 'category') active @endif">
        <a href="{{ route('category.index') }}">
            <i class="fa fa-list-ul"></i>
            <span class="link-title">Categories</span>
        </a>
    </li>
@endif
