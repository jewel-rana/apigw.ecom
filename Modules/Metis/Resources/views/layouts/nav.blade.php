<div id="top">
    <!-- .navbar -->
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <header class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#left">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ route('dashboard.index') }}" class="navbar-brand">
                    <img src="/images/logo.png" style="width: 230px; height: 60px; padding: 10px"/>
                </a>
            </header>

            {{--            <div class="topnav">--}}
            {{--                <div class="btn-group">--}}
            {{--                    <a data-placement="bottom" data-original-title="Fullscreen" data-toggle="tooltip"--}}
            {{--                       class="btn btn-default btn-sm" id="toggleFullScreen">--}}
            {{--                        <i class="glyphicon glyphicon-fullscreen"></i>--}}
            {{--                    </a>--}}
            {{--                </div>--}}
            {{--                <div class="btn-group">--}}
            {{--                    <a data-placement="bottom" data-original-title="E-mail" data-toggle="tooltip"--}}
            {{--                       class="btn btn-default btn-sm">--}}
            {{--                        <i class="fa fa-envelope"></i>--}}
            {{--                        <span class="label label-warning">5</span>--}}
            {{--                    </a>--}}
            {{--                    <a data-placement="bottom" data-original-title="Messages" href="#" data-toggle="tooltip"--}}
            {{--                       class="btn btn-default btn-sm">--}}
            {{--                        <i class="fa fa-comments"></i>--}}
            {{--                        <span class="label label-danger">4</span>--}}
            {{--                    </a>--}}
            {{--                    <a data-toggle="modal" data-original-title="Help" data-placement="bottom"--}}
            {{--                       class="btn btn-default btn-sm"--}}
            {{--                       href="#helpModal">--}}
            {{--                        <i class="fa fa-question"></i>--}}
            {{--                    </a>--}}
            {{--                </div>--}}
            {{--                <div class="btn-group">--}}
            {{--                    <a href="login.html" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom"--}}
            {{--                       class="btn btn-metis-1 btn-sm">--}}
            {{--                        <i class="fa fa-power-off"></i>--}}
            {{--                    </a>--}}
            {{--                </div>--}}
            {{--                <div class="btn-group">--}}
            {{--                    <a data-placement="bottom" data-original-title="Show / Hide Left" data-toggle="tooltip"--}}
            {{--                       class="btn btn-primary btn-sm toggle-left" id="menu-toggle">--}}
            {{--                        <i class="fa fa-bars"></i>--}}
            {{--                    </a>--}}
            {{--                    <a href="#right" data-toggle="onoffcanvas" class="btn btn-default btn-sm" aria-expanded="false">--}}
            {{--                        <span class="fa fa-fw fa-comment"></span>--}}
            {{--                    </a>--}}
            {{--                </div>--}}

            {{--            </div>--}}


            <!-- .nav -->
            <ul class="nav navbar-nav navbar-right profile-menu">
                <li class='dropdown '>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ auth()->user()->name ?? '' }} <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="profile-pic">
                            <img src="/images/user.png">
                            <p><strong>{{ auth()->user()->name ?? '' }}</strong></p>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{route('auth.profile')}}"><i class="fa fa-user"></i> My profile</a></li>
                        <li><a href="{{route('auth.change-password')}}"><i class="fa fa-cogs"></i> Change password</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-logout">
                            <form class="form-inline" method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- /.nav -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <!-- /.navbar -->

</div>
<!-- /#top -->
<div id="left">
    <div class="media user-media bg-light dker">
        {{--            <div class="user-media-toggleHover">--}}
        {{--                <span class="fa fa-user"></span>--}}
        {{--            </div>--}}
        <div class="user-wrapper">
            <a class="user-link" href="/dashboard/profile">
                <img class="media-object img-thumbnail user-avatar" alt="User Picture" src="/images/user.png">
                {{--                    <span class="label label-danger user-label">16</span>--}}
            </a>

            <div class="media-body" style="padding-top: 15px; padding-left: 10px;">
                <h5 class="media-heading">Welcome, {{ auth()->user()->name ?? '' }}</h5>
                <ul class="list-unstyled user-info">
                    <li>
                        <a href="/">{{ auth()->user()->roles->first()->name ?? '' }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #menu -->
    <ul id="menu" class="bg-blue dker">
        <li class="nav-divider"></li>
        <li>
            <a href="{{ route('dashboard.index') }}">
                <i class="fa fa-home"></i> <span class="link-title"> Dashboard</span>
            </a>
        </li>

        @php echo \Modules\Metis\MetisHelper::loadMenus(); @endphp
    </ul>
    <!-- /#menu -->
</div>
<!-- /#left -->
