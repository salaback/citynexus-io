<tr class="
     @if($upload->processed_at == null)
        warning
    @endif
        ">
    <td>{{$upload->note}}</td>
    <td>{{$upload->created_at->toFormattedDateString()}}</td>
    <td>
        @if($upload->processed_at != null)
            {{$upload->processed_at->toFormattedDateString()}}
        @else

        @endif
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