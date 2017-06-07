@extends('master.main')

@section('title', 'Organization Settings')

@section('main')
    <div class="row">
        <div class="col-md-6">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Organization</strong> Users</h1>
                    @can('citynexus', ['org-admin', 'users-create'])
                        <ul class="controls">
                            @can('citynexus', ['org-admin', 'users-create'])
                            <li><a href="{{route('users.create')}}"><i class="fa fa-plus mr-5"></i> Invite New User</a></li>
                            @endcan

                        </ul>
                    @endcan
                </div>
                <div class="boxs-body p-0" style="max-height: 400px; overflow: scroll;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody >
                        @foreach($users->sortBy('last_name') as $user)
                            <tr>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->info->department}}</td>
                                <td>{{$user->info->title}}</td>
                                <td><a href="{{route('users.edit', [$user->id])}}" class="btn btn-raised btn-primary btn-sm">Manage</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-md-6">
            <section class="boxs">
                <form role="form">
                    <div class="boxs-header dvd dvd-btm">
                        <h1 class="custom-font"><strong>General </strong>Settings</h1>
                    </div>
                    <div class="boxs-body">
                                <div class="form-group col-md-4">
                                    <label for="lat">Latitude </label>
                                    <input type="text" name="lat" id="lat" value="{{config('client.lat')}}" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="lng">Longitude</label>
                                    <input type="text" name="lng" id="lng" value="{{config('client.lng')}}" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="map_zoom">Map Zoom</label>
                                    <select type="" name="map_zoom" id="lng" class="form-control">
                                        <option value="1" @if(config('client.map_zoom') == 1) selected @endif>1</option>
                                        <option value="2" @if(config('client.map_zoom') == 2) selected @endif>2</option>
                                        <option value="3" @if(config('client.map_zoom') == 3) selected @endif>3</option>
                                        <option value="4" @if(config('client.map_zoom') == 4) selected @endif>4</option>
                                        <option value="5" @if(config('client.map_zoom') == 5) selected @endif>5</option>
                                        <option value="6" @if(config('client.map_zoom') == 6) selected @endif>6</option>
                                        <option value="7" @if(config('client.map_zoom') == 7) selected @endif>7</option>
                                        <option value="8" @if(config('client.map_zoom') == 8) selected @endif>8</option>
                                        <option value="9" @if(config('client.map_zoom') == 9) selected @endif>9</option>
                                        <option value="10" @if(config('client.map_zoom') == 10) selected @endif>10</option>
                                        <option value="11" @if(config('client.map_zoom') == 11) selected @endif>11</option>
                                        <option value="12" @if(config('client.map_zoom') == 12) selected @endif>12</option>
                                        <option value="13" @if(config('client.map_zoom') == 13) selected @endif>13</option>
                                        <option value="14" @if(config('client.map_zoom') == 14) selected @endif>14</option>
                                        <option value="15" @if(config('client.map_zoom') == 15) selected @endif>15</option>
                                        <option value="16" @if(config('client.map_zoom') == 16) selected @endif>16</option>
                                        <option value="17" @if(config('client.map_zoom') == 17) selected @endif>17</option>
                                        <option value="18" @if(config('client.map_zoom') == 18) selected @endif>18</option>
                                        <option value="19" @if(config('client.map_zoom') == 19) selected @endif>19</option>
                                        <option value="20" @if(config('client.map_zoom') == 10) selected @endif>20</option>

                                    </select>
                                </div>
                            <div class="row"></div>
                    </div>
                    <div class="boxs-footer">
                        <button type="submit" class="btn btn-raised btn-primary">Save Settings</button>
                    </div>
                </form>
            </section>
        </div>
        <div class="col-md-6">
            <section class="boxs ">
                <div class="boxs-header dvd dvd-btm">
                    <h1 class="custom-font"><strong>Organization</strong> Groups</h1>
                    @can('citynexus', ['org-admin', 'groups'])
                        <ul class="controls">
                            @can('citynexus', ['org-admin', 'groups'])
                                <li><a href="{{route('groups.create')}}"><i class="fa fa-plus mr-5"></i> Create New Group</a></li>
                            @endcan

                        </ul>
                    @endcan
                </div>
                <div class="boxs-body p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Users</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups->sortBy('name') as $group)
                            <tr>
                                <td>{{$group->name}}</td>
                                <td>{{$group->userCount}}</td>
                                <td><a href="{{route('groups.edit', [$group->id])}}" class="btn btn-raised btn-primary btn-sm">Manage</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>


@endsection