@extends('master.vue')

@section('content')

    <example></example>

@endsection

@push('vue-script')

<script>
    Vue.component('example', {
        template: '<div>A custom component!</div>'
    })
</script>

@endpush