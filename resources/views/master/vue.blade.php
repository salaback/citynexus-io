@extends('master.main')

@section('main')

    @yield('content')

@endsection

@push('scripts')

    @stack('vue-script')

    <script>


        new Vue({
            el: '#content'
            // options
        });


    </script>

@endpush