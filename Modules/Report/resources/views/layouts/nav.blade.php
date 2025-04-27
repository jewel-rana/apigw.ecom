@if(\App\Helpers\CommonHelper::hasPermission(['report-list', 'report-generate', 'report-download']))
{{--    <li class="@if($module_name == 'report') active @endif">--}}
{{--        <a href="{{ route('report.index') }}">--}}
{{--            <i class="fa fa-bar-chart"></i>--}}
{{--            <span class="link-title">Reports</span>--}}
{{--        </a>--}}
{{--    </li>--}}
@endif
