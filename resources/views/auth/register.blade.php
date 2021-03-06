@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col s8 offset-s2" style="border: 2px white solid; margin-top: 70px; padding: 20px;">

        <h5 style="color: #00979c; text-align: center;margin-bottom: 10px;">Register</h5>
        <hr style="margin-bottom: 15px;">
            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                    <div class="row">
                    <div class="col s6">
                            <label for="name" class="" style="font-size: 20px;">Username</label>
                            <input id="name" type="text" class="" style="color: white;" name="name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="">
                                    <strong style="color: white">{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="col s6">
                        <label for="email" class="" style="font-size: 20px;">E-Mail</label>
                        <input id="email" type="email" class=""  style="color: white;" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="">
                                <strong style="color: white">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col s6">
                        <label for="password" class="" style="font-size: 20px;">Password</label>
                        <input id="password" type="password" class="" style="color: white;" name="password" required>
                        @if ($errors->has('password'))
                            <span class="">
                                <strong style="color: white">{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col s6">
                        <label for="password-confirm" class="col-md-4 control-label" style="font-size: 20px;">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" style="color: white;" name="password_confirmation" required>
                    </div>

                    <div class="col s6">
                        <label for="country" class="" style="font-size: 20px;">Country</label>
                        <input id="country" type="text" class="" style="color: white;" name="country" value="{{ old('country') }}" required>
                        @if ($errors->has('country'))
                            <span class="">
                                <strong style="color: white">{{ $errors->first('country') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col s6">
                        <label for="phone" class="" style="font-size: 20px;">Phone</label>
                        <input id="phone" type="text" class=""  style="color: white;" name="phone" value="{{ old('phone') }}" required>
                        @if ($errors->has('phone'))
                            <span class="">
                                <strong style="color: white">{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col s12 center">
                        <button type="submit" class="btn" style="background-color:#00979c;font-size: 20px;" id="button">
                                    Register
                        </button>
                    </div>

                    </div>
            </form>

        </div>
    </div>
</div>

@endsection
<div style="position: absolute; left: 0; bottom: 0; width:8.3333%; height: 11.25vh; background-color: white">
    
</div>