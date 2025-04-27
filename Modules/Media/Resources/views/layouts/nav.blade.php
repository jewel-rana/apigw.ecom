@if(\App\Helpers\CommonHelper::hasPermission(['media-list', 'media-create', 'media-show', 'media-action']))
    <li class="@if($module_name == 'media') active @endif">
        <a href="{{ route('media.index') }}">
            <i class="fa fa-image"></i>
            <span class="link-title">Medias</span>
        </a>
    </li>
@endif
