@extends('master.auth')

@section('main')

    <form class="form" method="POST" action="{{ action('AuthController@postLogin') }}">
        {{csrf_field()}}
        <h3 class="mt-0">Log In</h3>

        @if(\Illuminate\Support\Facades\Session::exists('flash_info'))
            <div class="alert alert-info">
                {{\Illuminate\Support\Facades\Session::get('flash_info')}}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-warning">
                @foreach ($errors->all() as $error)
                {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <div class="content">
            <div class="form-group">
                <input type="email" name="email" class="form-control underline-input" placeholder="Enter Your Email">
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password..." class="form-control underline-input">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" unchecked>
                    Remember me</label>
            </div>
        </div>
        <div class="footer text-center">
            <button role="button" class="btn btn-primary btn-raised">Login<div class="ripple-container"></div></button>
        </div>
        <a href="{{ '/password/reset' }}" class="btn btn-primary btn-wd btn-lg">Forgot Password?</a>
    </form>
@endsection
