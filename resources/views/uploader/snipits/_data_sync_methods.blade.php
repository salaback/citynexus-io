    <section class="boxs">
        <div class="boxs-header dvd dvd-btm">
            <h1 class="custom-font"><strong>Data Sync Methods</strong></h1>
            <ul class="controls">
                <li class="dropdown"> <a role="button" tabindex="0" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-plus"></i> Create New Sync<i class="fa fa-angle-down ml-5"></i></a>
                    <ul class="dropdown-menu pull-right with-arrow animated littleFadeInUp">
                        <li><a href="{{route('uploader.addressSync', [$uploader->id])}}"> Create Address Sync</a></li>
                        <li><a href="{{route('uploader.entitySync', [$uploader->id])}}"> Create Entity Sync</a></li>
                        <li><a href="{{route('uploader.tagSync', [$uploader->id])}}"> Create Tag Sync</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="boxs-body">

            <div class="inbox-widget nicescroll" style="height: 315px; overflow: scroll; outline: none;" tabindex="5000">
                @if($uploader->syncs != null)
                    @foreach($uploader->syncs as $sync)

                        <div class="inbox-item">
                            <form action="{{route('uploader.removeSync', [$uploader->id])}}" method="post">
                                {{csrf_field()}}
                                <button role="button" href="#" class="pull-right"><span class="fa fa-trash"></span></button>
                                <input type="hidden" name="sync" value="{{json_encode($sync)}}">
                            </form>
                            <div class="col-xs-2">
                                @if($sync['class'] == 'address')
                                    <span class="fa fa-building fa-2x inbox-item-img"></span><br>
                                @elseif($sync['class'] == 'entity')
                                    <span class="fa fa-user fa-2x inbox-item-img"></span><br>
                                @endif
                            </div>
                            <div class="col-xs-10">
                                @if($sync['class'] == 'address')
                                    <p class="inbox-item-author">{{ucwords($sync['class'])}}</p>
                                    @if(isset($sync['full_address']))
                                        Full Address: <span class="label label-default">{{$sync['full_address']}}</span>
                                    @else
                                        @if(isset($sync['house_number']))Street Number: <span class="label label-default">{{$sync['house_number']}}</span>@endif
                                        @if(isset($sync['street_name']))Street Name: <span class="label label-default">{{$sync['street_name']}}</span>@endif
                                        @if(isset($sync['street_type']))Street Type: <span class="label label-default">{{$sync['street_type']}}</span>@endif
                                        @if(isset($sync['unit']))Unit: <span class="label label-default">{{$sync['unit']}}</span>@endif
                                    @endif
                                    @if(isset($sync['city']))City: <span class="label label-default">{{ $sync['city'] }}</span>@endif
                                    @if(isset($sync['state']))State: <span class="label label-default">{{ $sync['state']}}</span>@endif
                                    @if(isset($sync['postal_code']))Postal Code: <span class="label label-default">{{$sync['postal_code']}}</span>@endif

                                @elseif($sync['class'] == 'entity')
                                    <p class="inbox-item-author">{{ucwords($sync['class'])}}</p>
                                    @if(isset($sync['full_name']))
                                        Full Name: <span class="label label-default">{{$sync['full_name']}}</span>
                                    @else
                                        Title: <span class="label label-default">{{$sync['title'] ?: 'NULL'}}</span>
                                        First Name: <span class="label label-default">{{$sync['first_name'] ?: 'NULL'}}</span>
                                        Middle Name: <span class="label label-default">{{$sync['middle_name'] ?: 'NULL'}}</span>
                                        Last Name: <span class="label label-default">{{$sync['last_name'] ?: 'NULL'}}</span>
                                        Suffix: <span class="label label-default">{{$sync['suffix'] ?: 'NULL'}}</span>
                                    @endif
                                    Role: <span class="label label-default">{{$sync['role'] ?: 'NULL'}}</span>
                                @elseif($sync['class'] == 'tag')
                                    <p class="inbox-item-author">{{ucwords($sync['class'])}}</p>
                                    @if(isset($sync['full_name']))
                                        Full Name: <span class="label label-default">{{$sync['full_name']}}</span>
                                    @else
                                        Title: <span class="label label-default">{{$sync['title'] ?: 'NULL'}}</span>
                                        First Name: <span class="label label-default">{{$sync['first_name'] ?: 'NULL'}}</span>
                                        Middle Name: <span class="label label-default">{{$sync['middle_name'] ?: 'NULL'}}</span>
                                        Last Name: <span class="label label-default">{{$sync['last_name'] ?: 'NULL'}}</span>
                                        Suffix: <span class="label label-default">{{$sync['suffix'] ?: 'NULL'}}</span>
                                    @endif
                                    Role: <span class="label label-default">{{$sync['role'] ?: 'NULL'}}</span>
                                @endif


                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        You have no sync settings! Please choose at least one.
                    </div>
                @endif
            </div>
        </div>
    </section>