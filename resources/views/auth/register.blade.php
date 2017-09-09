@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col s4 offset-s4" style="border: 2px white solid; margin-top: 15px; padding: 20px;">

        <h5 style="color: #00979c; text-align: center;margin-bottom: 10px;">Register</h5>
        <hr style="margin-bottom: 15px;">
            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                    <div class="">
                            <label for="name" class="">Name</label>
                            <input id="name" type="text" class="" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="">
                        <label for="email" class="">E-Mail Address</label>
                        <input id="email" type="email" class="" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="">
                        <label for="password" class="">Password</label>
                        <input id="password" type="password" class="" name="password" required>
                        @if ($errors->has('password'))
                            <span class="">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="">
                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="">
                        <label for="date" class="">Date Of Birth</label>
                        <input id="date" type="date" class="" name="date" required>
                        @if ($errors->has('date'))
                            <span class="">
                                <strong>{{ $errors->first('date') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="">
                        <label for="country" class="">Country</label>
                        <input id="country" type="text" class="" name="country" value="{{ old('country') }}" required>
                        @if ($errors->has('country'))
                            <span class="">
                                <strong>{{ $errors->first('country') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="">
                        <label for="phone" class="">Phone</label>
                        <input id="phone" type="text" class="" name="phone" value="{{ old('phone') }}" required>
                        @if ($errors->has('phone'))
                            <span class="">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="">
                        <button type="submit" class="btn" id="button">
                                    Register
                        </button>
                    </div>
            </form>

        </div>
    </div>
</div>

@endsection
