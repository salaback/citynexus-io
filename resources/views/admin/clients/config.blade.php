@extends('master.main')


@section('main')

    <form action="/admin/client/config/{{$client->id}}" method="POST" class="form-horizontal" role="form">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="panel-title">Client Configs: {{$client->name}}</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="config[app_key]" class="col-sm-2 control-label">APP Key</label>
                    <div class="col-sm-10">
                        <input type="password" name="config[app_key]" id="inputID" class="form-control" title="" value="{{$config['app_key'] ?: ''}}" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="config[city]" class="col-sm-2 control-label">City</label>
                    <div class="col-sm-10">
                        <input type="text" name="config[city]" id="inputID" class="form-control" title="" value="{{$config['city'] ?: ''}}" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="config[state]" class="col-sm-2 control-label">State</label>
                    <div class="col-sm-10">
                        <input type="text" name="config[state]" id="inputID" class="form-control" title="" value="{{$config['state'] ?: ''}}" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="config[map_lat]" class="col-sm-2 control-label">Map Center Lat</label>
                    <div class="col-sm-10">
                        <input type="text" name="config[map_lat]" id="inputID" class="form-control" title="" value="{{$config['map_lat'] ?: ''}}" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="config[map_lng]" class="col-sm-2 control-label">Map Center Lng</label>
                    <div class="col-sm-10">
                        <input type="text" name="config[map_lng]" id="inputID" class="form-control" title="" value="{{$config['map_lng'] ?: ''}}" >
                    </div>
                </div>

                <div class="form-group">
                    <label for="config[map_zoom]" class="col-sm-2 control-label">Map Center Lng</label>
                    <div class="col-sm-10">
                        <select name="config[map_zoom]" id="" class="form-control">
                            <option value="1" @if(isset($config['map_zoom']) && $config['map_zoom'] == 1)selected @endif>1</option>
                            <option value="2" @if(isset($config['map_zoom']) && $config['map_zoom'] == 2)selected @endif>2</option>
                            <option value="3" @if(isset($config['map_zoom']) && $config['map_zoom'] == 3)selected @endif>3</option>
                            <option value="4" @if(isset($config['map_zoom']) && $config['map_zoom'] == 4)selected @endif>4</option>
                            <option value="5" @if(isset($config['map_zoom']) && $config['map_zoom'] == 5)selected @endif>5</option>
                            <option value="6" @if(isset($config['map_zoom']) && $config['map_zoom'] == 6)selected @endif>6</option>
                            <option value="7" @if(isset($config['map_zoom']) && $config['map_zoom'] == 7)selected @endif>7</option>

                            <option value="8" @if(isset($config['map_zoom']) && $config['map_zoom'] == 8)selected @endif>8</option>
                            <option value="9" @if(isset($config['map_zoom']) && $config['map_zoom'] == 9)selected @endif>9</option>
                            <option value="10" @if(isset($config['map_zoom']) && $config['map_zoom'] == 10)selected @endif>10</option>
                            <option value="11" @if(isset($config['map_zoom']) && $config['map_zoom'] == 11)selected @endif>11</option>
                            <option value="12" @if(isset($config['map_zoom']) && $config['map_zoom'] == 12)selected @endif>12</option>
                            <option value="13" @if(isset($config['map_zoom']) && $config['map_zoom'] == 13)selected @endif>13</option>
                            <option value="14" @if(isset($config['map_zoom']) && $config['map_zoom'] == 14)selected @endif>14</option>
                            <option value="15" @if(!isset($config['map_zoom']))selected @endif>15</option>
                            <option value="16" @if(isset($config['map_zoom']) && $config['map_zoom'] == 16)selected @endif>16</option>
                            <option value="17" @if(isset($config['map_zoom']) && $config['map_zoom'] == 17)selected @endif>17</option>
                            <option value="18" @if(isset($config['map_zoom']) && $config['map_zoom'] == 18)selected @endif>18</option>
                            <option value="19" @if(isset($config['map_zoom']) && $config['map_zoom'] == 19)selected @endif>19</option>
                            <option value="20" @if(isset($config['map_zoom']) && $config['map_zoom'] == 20)selected @endif>20</option>
                            <option value="21" @if(isset($config['map_zoom']) && $config['map_zoom'] == 21)selected @endif>21</option>
                            <option value="22" @if(isset($config['map_zoom']) && $config['map_zoom'] == 22)selected @endif>22</option>
                            <option value="23" @if(isset($config['map_zoom']) && $config['map_zoom'] == 23)selected @endif>23</option>
                            <option value="24" @if(isset($config['map_zoom']) && $config['map_zoom'] == 24)selected @endif>24</option>
                            <option value="25" @if(isset($config['map_zoom']) && $config['map_zoom'] == 25)selected @endif>25</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="config[gmap_API]" class="col-sm-2 control-label">Google API Key</label>
                    <div class="col-sm-10">
                        <input type="password" name="config[gmap_API]" id="inputID" class="form-control" title="" value="{{$config['gmap_API'] ?: null}}" >
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-primary">Update Client</button>
            </div>
        </div>
    </form>

@endsection