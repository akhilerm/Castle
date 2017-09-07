@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">

        <div class="col s8 offset-s2">
            <p>
                @if(\Illuminate\Support\Facades\Session::has('message'))
                    {{ \Illuminate\Support\Facades\Session::get('message') }}
                @endif
            </p>

            <form class="" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="">
                    <label for="name" class="col s4">username</label>
                    <input id="name" type="text" class="" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="">
                    <label for="password" class="col s4">Password</label>
                    <input id="password" type="password" class="" name="password" required>
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
                    <button type="submit" class="btn">
                        Login
                    </button>
                    <a class="" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
