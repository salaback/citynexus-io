@extends('email.basic_email');

@section("content")

    <p>
        You have been invited to join CityNexus!
    </p>
    <p>
        To activate your account follow this link: {{ url('/activate-account?key=' . $user->activation) }}
    </p>
    <p>
        Please note that this platform is still in beta development and some features may not work as they should.
        If you encounter a function which is not working, require support, or have a feature request please
        click the "Submit Support Ticket" button in the left hand menu.  Thank you for your patience and cooperation.
    </p>

    <small>(C) {{date('Y')}} CityNexus</small>
@stop