@extends('metis::layouts.master')

@section('header')
@endsection

@section('content')
    <div id="content">
        <div class="outer">
            <div class="inner bg-light no-padding">
                <h3><i class="fa fa-plus"></i> {{ $title ?? 'Add new provider'}}</h3>
                <div>
                    <ul class="nav nav-tabs menuTab" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab"
                               aria-expanded="true">Info</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="home">
                            <div class="row">
                                <div class="col-lg-7">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form action="{{ route('provider.user.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group @error('name') has-error @enderror">
                                            <label for="">Provider (*)</label>
                                            <select class="form-control select2 select2-blue" name="provider_id"
                                                    data-placeholder="Select provider" required>
                                                @foreach(app(\Modules\Provider\Services\ProviderService::class)->all() as $provider)
                                                    <option value="{{ $provider->id }}"
                                                            @if($provider->id == old('provider_id')) selected @endif>{{ $provider->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('provider_id')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('name') has-error @enderror">
                                            <label for="">Name (*)</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                   class="form-control form-control-lg" id="" placeholder="Name"
                                                   required>
                                            @error('name')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('name') has-error @enderror">
                                            <label for="">Email (*)</label>
                                            <input type="text" name="email" value="{{ old('email') }}"
                                                   class="form-control form-control-lg" id="" placeholder="Email"
                                                   required>
                                            @error('email')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('name') has-error @enderror">
                                            <label for="">Mobile (*)</label>
                                            <input type="text" name="mobile" value="{{ old('mobile') }}"
                                                   class="form-control form-control-lg" id="" placeholder="Mobile"
                                                   required>
                                            @error('mobile')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('password') has-error @enderror">
                                            <label for="">Password (*)</label>
                                            <input type="password" name="password" value="{{ old('password') }}"
                                                   class="form-control form-control-lg" id="" placeholder="Password"
                                                   required>
                                            @error('password')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('password') has-error @enderror">
                                            <label for="">Password confirm (*)</label>
                                            <input type="password" name="password_confirm"
                                                   value="{{ old('password_confirm') }}"
                                                   class="form-control form-control-lg" id=""
                                                   placeholder="Password confirm"
                                                   required>
                                            @error('password_confirm')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group @error('status') has-error @enderror">
                                            <label for="">Status? (*)</label>
                                            <select name="status" class="form-control">
                                                <option value="active" @if(old('status') == 'active') selected @endif>Active
                                                </option>
                                                <option value="inactive" @if(old('status') == 'inactive') selected @endif>Inactive
                                                </option>
                                            </select>
                                            @error('status')
                                            <div class="help-block with-errors text-danger"><i
                                                    class="fa fa-times"></i> {{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">CREATE</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.inner -->
        </div>
        <!-- /.outer -->
    </div>
    <!-- /#content -->
@endsection
@section('footer')

@endsection
