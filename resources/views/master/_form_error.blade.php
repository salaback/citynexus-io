<script>
@if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                alert('warning', {{ $error }})
            @endforeach
@endif
</script>