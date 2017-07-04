<section class="boxs">
    <div class="boxs-header dvd dvd-btm">
        <h1 class="custom-font"><strong>Upload History</strong></h1>
    </div>
    <div class="boxs-body">
        <table class="table m-b-0">
            <thead>
            <tr>
                <th>Description</th>
                <th>Upload Date</th>
                <th>Process Date</th>
                <th>Records</th>
                <th>Uploaded By</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php($uploads = $uploader->uploads()->paginate(10))
            @foreach($uploads as $upload)
                <tr class="
                        @if($upload->processed_at == null)
                        warning
                    @endif
                        ">
                    <td>{{str_limit($upload->description, 100, '...')}}</td>
                    <td>{{$upload->created_at->toFormattedDateString()}}</td>
                    <td>
                        @if($upload->processed_at != null)
                            {{$upload->processed_at->toFormattedDateString()}}
                        @else

                        @endif
                    </td>
                    <td>
                        {{$upload->count}}
                    </td>
                    <td>{{$upload->user->fullname}}</td>
                    <td><div class="dropup pull-right">
                            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-navicon" id="icon-{{$upload->id}}"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{$upload->source}}" target="_blank"><i class="fa fa-download"></i> Download Original File</a></li>

                                <li class="divider"></li>
                                <li><a href="#" onclick="refreshUpload({{$upload->id}})" id="refresh-{{$upload->id}}"><i class="fa fa-refresh"></i> Refresh Upload</a></li>
                                <li><a href="#"> <i class="fa fa-trash"></i> Remove Upload</a></li>
                            </ul>
                        </div></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $uploads->links() }}
    </div>
</section>