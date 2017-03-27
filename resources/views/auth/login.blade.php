@extends('master.auth')

@section('main')

    <form class="form" method="POST" action="{{ action('AuthController@postLogin') }}">
        {{csrf_field()}}
        <h3 class="mt-0">Log In</h3>

    @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
        <a href="{{ url('/password/email') }}" class="btn btn-primary btn-wd btn-lg">Forgot Password?</a>
    </form>
@endsection
