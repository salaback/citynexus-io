
<script>

    var start;
    var drop;
    var agendaItems = [{
            type: 'heading',
            name: 'Heading',
            content: 'Agenda Heading'}];

        agendaItems[Math.random().toString(36).slice(2)] = {
            type: 'heading',
            name: 'Heading',
            content: 'Agenda Heading'};

    var agendaElements = {
        heading:   {
            type: 'heading',
            name: 'Heading',
            content: 'Agenda Heading'},
        text: {
            type: 'text',
            name: 'Text Area',
            content: 'This is agenda text block.'},
        property: {
            type: 'property',
            name: 'Property',
            property_id: null}
    };

    var properties = {!! \App\PropertyMgr\Model\Property::all()->pluck('id', 'address') !!};

    var drugItem = 'heading';

    var app = new Vue({
        el: '#app',
        data: {
            title: 'Agenda',
            agenda_items: agendaItems,
            agenda_elements: agendaElements,
            properties: properties,
            property: null
        },
        methods: {

            drop: function(e) {

                console.log('drag drop');

                agendaItems.push(agendaElements[drugItem]);

                drop = e;
            },
            dragstart: function(e) {

                console.log('drag start');

                drugItem = e.target.id;
                start = e;
            },
        }
    });

</script>

<script type="text/javascript">
    $('select').select2();
</script>