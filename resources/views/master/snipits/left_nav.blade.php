<ul id="navigation">
    {{--<li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>--}}
    @can('citynexus', ['properties', 'view'])<li><a href="{{route('properties.index')}}"><i class="fa fa-home"></i> <span>Properties</span></a></li> @endcan
    @can('citynexus', ['entities', 'view'])<li><a href="{{route('entity.index')}}"><i class="fa fa-user"></i> <span>Entities</span></a></li> @endcan
    @can('citynexus', ['dataviz', 'view'])
        <li> <a role="button" tabindex="0"><i class="fa fa-bar-chart-o"></i> <span>Data Visualization</span></a>
            <ul>
                @can('citynexus', ['dataviz', 'maps'])
                    <li><a href="{{route('map')}}"><i class="fa fa-angle-right"></i>Mapping</a></li>
                @endcan
            </ul>
        </li>
    @endcan
    @can('citynexus', ['datasets', 'view'])
        <li> <a role="button" tabindex="0"><i class="fa fa-database"></i> <span>Data Sets</span></a>
            <ul>
                <li><a href="{{route('dataset.index')}}"><i class="fa fa-angle-right"></i>All Data Sets</a></li>
                @can('citynexus', ['datasets', 'create'])<li><a href="{{route('dataset.create')}}"><i class="fa fa-angle-right"></i>Create New Data Set</a></li>@endcan

            </ul>
        </li>
    @endcan
    @can('citynexus', ['client-admin', 'view'])<li><a href="/organization"><i class="fa fa-group"></i> <span>Organization Settings</span></a></li>@endcan
    @can('super-admin') <li><a href="/admin"><i class="fa fa-group"></i> <span>Client Admin</span></a></li> @endcan

</ul>
<!--/ NAVIGATION Content -->