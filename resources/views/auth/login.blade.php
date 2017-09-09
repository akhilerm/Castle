@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">

        <div class="col s4 offset-s4" style="border: 2px white solid; margin-top: 140px; padding: 20px;">
            <p>
                @if(\Illuminate\Support\Facades\Session::has('message'))
                    {{ \Illuminate\Support\Facades\Session::get('message') }}
                @endif
            </p>

            <form class="" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="">
                    <label for="name" class="col s4">username</label>
                    <input  id="name" type="text" class="" name="name" style="color: white;" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="">
                    <label for="password" class="col s4">Password</label>
                    <input id="password" type="password" class="" style="color: white;" name="password" required>
                    @if ($errors->has('password'))
                        <span class="">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="">
                    <p>
                        <input type="checkbox"  id= "remember" name="remember" {{ old('remember') ? 'checked' : '' }} >
                        <label for="remember">Remember Me</label>
                    </p>
                </div>

                <div class="">
                    <button type="submit" class="btn" style="background-color:#00979c">
                        Login
                    </button>
                    <a class="" style="color: #00979c" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
