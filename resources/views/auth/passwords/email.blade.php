@extends('master.auth')

@section('main')
        <form class="form" method="POST" action="{{ route('password.email') }}">
            {{csrf_field()}}
            <h3 class="mt-0">Reset Password</h3>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="content">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <div class="form-group">
                        <input id="email" type="email" name="email" class="form-control underline-input" value="{{ old('email') }}" placeholder="Enter Your Email">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    </div>
                </div>

            </div>
            <div class="footer text-center">
                <div>
                    <button type="submit" class="btn btn-primary btn-raised">
                        Send Password Reset Link
                    </button>
                </div>
            </div>
        </form>

@endsection
