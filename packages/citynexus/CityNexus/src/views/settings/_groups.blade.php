<div class="col-md-9">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Group Name</th>
            <th>Members</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td>{{$group->name}}</td>
                <td>{{$group->users->count()}}</td>
                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="col-sm-3">
    <a href="{{action('Auth\UserGroupController@create')}}" class="btn btn-primary pull-right" >Create New Group</a>
</div>

@push('js_footer')

<script>

</script>
@endpush