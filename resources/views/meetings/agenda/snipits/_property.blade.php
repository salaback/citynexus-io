<a href="{{route('properties.show', [$property['id']])}}" target="_blank" class="btn btn-primary btn-raised pull-right">Property Detail</a>
<strong>{{$property['address']}}</strong><br>
@if(isset($property['note']))<p>{{$property['note']}}</p>@endif

