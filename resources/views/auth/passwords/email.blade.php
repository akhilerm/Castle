@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s4 offset-s4"  style="border: 2px white solid; margin-top: 250px; padding: 20px;">
            <div class="panel panel-default">
            
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                     <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label" style="font-size: 20px;">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" style="color: white;" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block" style="color: white; padding: 5px;">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group" >
                            <div>
                                <button type="submit" class="btn btn-primary" style="font-size: 20px;">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<div style="position: absolute; left: 0; bottom: 0; width:8.3333%; height: 11.25vh; background-color: white">
    
</div>
