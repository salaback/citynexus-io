@extends('master.auth')

@section('main')

    @if(count($errors) > 0)
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form id="activateForm" class="form" method="POST" action="{{ action('AuthController@activate') }}" onsubmit="return validateForm()">
        {{csrf_field()}}
        <input type="hidden" name="key" value="{{$key}}">
        <input type="hidden" name="termsId" value="{{$terms->id}}">
        <h3 class="mt-0">Activate Account</h3>

        <div class="content">
            <div class="terms-of-service">
                <h4>Terms of Service</h4>
                {!! $terms->terms !!}
            </div>

            <div class="checkbox">
                <label class="checkbox checkbox-custom">
                    <input type="checkbox" name="agree" id="agree" required><span class="checkbox-material"></span>
                    I agree to the Terms of Service</label><ul class="parsley-errors-list" id="parsley-id-multiple-agree"></ul>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password..." class="form-control underline-input">
            </div>
            <div class="form-group">
                <input type="password" name="password_confirmation" placeholder="Confirm Password..." class="form-control underline-input">
            </div>
        </div>

        <div class="footer text-center" id="submit">
            <button role="button" class="btn btn-primary btn-raised">Activate Account<div class="ripple-container"></div></button>
        </div>

    </form>
@endsection


@push('style')

<style>
    .terms-of-service {
        text-align: left;
        border: #0a6aa1;
        border-width: 1px;
        max-height: 150px;
        overflow: scroll;
    }
</style>

@endpush

@push('scripts')

    <script>
        function validateForm() {
            var x = document.forms["activateForm"]["password"].value;
            var y = document.forms["activateForm"]["password_confirmation"].value;
            var agree = document.getElementById('agree');

            if (x != y) {
                alert("Passwords must match.");
                return false;
            }

            if (x.length < 8) {
                alert("Passwords must be at least 8 characters.");
                return false;
            }

            if (!agree.checked)
            {
                alert("You must agree to the Terms of Service.");
                return false;
            }
        }
    </script>

@endpush