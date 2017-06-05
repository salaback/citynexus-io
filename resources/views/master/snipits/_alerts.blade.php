<script>
    var alert = function(level, message)
    {
        if(message == null)
        {
            customAlert('info', level);
        }
        else
        {
            customAlert(level, message)
        }
    };

    var customAlert = function(level, message)
    {
        $.notify({
            // options
            message: message,
        },{
            // settings
            type: level,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
        });
    }

    @if(\Illuminate\Support\Facades\Session::has('flash_success'))
        alert("success", "{{\Illuminate\Support\Facades\Session::get('flash_success')}}");
    @endif

    @if(\Illuminate\Support\Facades\Session::has('flash_warning'))
        alert("warning", "{{\Illuminate\Support\Facades\Session::get('flash_warning')}}");
    @endif

    @if(\Illuminate\Support\Facades\Session::has('flash_danger'))
        alert("error", "{{\Illuminate\Support\Facades\Session::get('flash_danger')}}");
    @endif

    @if(\Illuminate\Support\Facades\Session::has('flash_info'))
        alert("info", "{{\Illuminate\Support\Facades\Session::get('flash_info')}}");
    @endif

</script>