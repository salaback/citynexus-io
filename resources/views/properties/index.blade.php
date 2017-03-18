@extends('master.main')

@section('properties', 'All Properties')

@section('main')

    <properties-wrapper></properties-wrapper>

    <div id="properties-wrapper"></div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.3/axios.min.js"></script>
<script>
    new Vue({
        el: '#content',
        // options

        mounted() {
            axios.get("{{route('properties.index')}}").then(response => console.log(response))
        }
    });
</script>

@endpush