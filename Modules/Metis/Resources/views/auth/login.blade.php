@extends('metis::layouts.auth')

@section('content')
    <div class="form-signin">
        <div class="text-center">
            <img src="/images/logo.png" class="logo" alt="Metis Logo">
        </div>
        <hr>
        <div class="tab-content">
            <div id="login" class="tab-pane active">
                <form action="{{ route('auth.login.post') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                    <p class="text-muted text-center">
                        Enter your username and password
                    </p>
                    @endif
                    <input name="email" value="{{ old('email') }}" type="text" placeholder="Emailddd" class="form-control top is-invalid">
                    <input name="password" value="{{ old('password') }}" type="password" placeholder="Password" class="form-control bottom">
                    <div class="checkbox">
                        <label>
                            <input name="rememberme" type="checkbox"> Remember Me
                        </label>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </form>
            </div>
            <div id="forgot" class="tab-pane">
                <form action="{{ route('auth.login') }}" method="POST">
                    @csrf
                    <p class="text-muted text-center">Enter your valid e-mail</p>
                    <input name="email" value="{{ old('email') }}" type="email" placeholder="Email address" class="form-control">
                    <br>
                    <button class="btn btn-lg btn-danger btn-block" type="submit">Recover Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
