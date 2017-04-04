<div class="row">
    <form id="filter-settings">
        @yield('main')
    </form>
    <br>
    <div class="row hidden" id="button-row">
        <div class="btn btn-primary" onclick="saveFilters('{{$uid}}')">Apply Filters</div>
    </div>
</div>