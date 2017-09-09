@extends('layouts.app')

@section('css')
@endsection

@section('topscript')
@endsection

@section('content')
    <div class="container">
        @if(\Illuminate\Support\Facades\Session::has('message'))
            <p> {{\Illuminate\Support\Facades\Session::get('message')}}</p>
        @endif
        <form class="" method="POST" enctype="multipart/form-data" action="{{ route('add') }}">
            {{ csrf_field() }}
            <div class="">
                <label for="question" class="">Question Name</label>
                <input id="question" type="text" class="" name="name" value="{{ old('question') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="">
                <label for="level" class="">Level</label>
                <input id="level" type="number" class="" name="level" value="{{ old('email') }}" required>
                @if ($errors->has('level'))
                    <span class="">
                        <strong>{{ $errors->first('level') }}</strong>
                    </span>
                @endif
            </div>

            <div class="">
                <label for="sublevel" class="">Sub level</label>
                <input id="sublevel" type="number" class="" name="sublevel" required>
                @if ($errors->has('sublevel'))
                    <span class="">
                                <strong>{{ $errors->first('sublevel') }}</strong>
                    </span>
                @endif
            </div>

            <div class="">
                <label for="time" class="">Sub level</label>
                <input id="time" type="number" class="" name="time" required>
                @if ($errors->has('time'))
                    <span class="">
                        <strong>{{ $errors->first('time') }}</strong>
                    </span>
                @endif
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <span>Constraints</span>
                    <input type="file" name="constraints" required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <span>Read Me</span>
                    <input type="file" name="readme" required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <span>Answers</span>
                    <input type="file" name="answers" required>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>

            <div class="">
                <button type="submit" class="btn" id="button">
                    Register
                </button>
            </div>

    </div>
@endsection

@section('bottomscript')
@endsection