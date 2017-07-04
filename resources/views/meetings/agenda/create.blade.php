@extends('master.main')

@section('title', 'Create Meeting Agenda')

@section('main')
    <div id="app">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="font-thin h3 m-0">
                    Create New Meeting Agenda
                </h1>
            </div>
        </div>
        <div class="col-sm-12"><br></div>
        <div class="row">
            <div class="col-sm-3">
                <section class="boxs">

                    <div class="box-body">
                        <div class="col-sm-12">
                            <div v-for="element in agenda_elements" id="app" >
                                <div @dragstart="dragstart" v-bind:id="element.type" class="draggable-options" draggable="true"> @{{ element.name }} </div>
                            </div>

                        <select class="select" id="property">
                            <label for="property">Property</label>

                            <option v-for="(index, property) in properties" v-bind:value="index">
                                @{{ property }}
                            </option>

                            <div v-for="element in agenda_elements" id="app" >
                                <div @dragstart="dragstart" v-bind:id="element.type" class="draggable-options" draggable="true"> @{{ element.name }} </div>
                    </div>
                        </select>
                        </div>
                    </div>

                    <div class="row"></div>
                </section>
            </div>
            <div class="col-sm-9">
                <section class="boxs">
                    <div class="boxs-body" id="agenda_items">
                        <div v-for="item in agenda_items">
                            <input v-if="item.type === 'heading'" type="text" v-model="item.content" class="inline-input heading">
                            <textarea v-if="item.type === 'text'" v-model="item.content" class="inline-input"></textarea>
                        </div>
                    </div>
                    <div class="boxs-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div @dragover.prevent @drop="drop" class="dropzone" id="dropzone">Add New Element Here</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </div>
@endsection

@push('style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<style>
    .dropzone {
        background-color: lightgrey;
        font-size: 25px;
        padding: 25px;
        text-align: center;
        border-width: 1px;
        border-color: black;
        border: solid;
    }

    .dropzone:-moz-drag-over {
        background-color: red;
    }

    #agenda_items .inline-input {
        background-color: transparent;
        border: 0px solid;
        width: 100%;
    }

    #agenda_items .heading {
        font-size: 24px;
        font-family: sans-serif, Verdana;
    }

    .draggable-options {
        text-align: center;
        padding: 15px;
        margin-bottom: 10px;
        border-color: white;
        background-color: lightgrey;
    }
</style>

@endpush

@push('scripts')
    <script src="/js/vue/vue.js" ></script>
    <script src="/js/vue/Sortable.js" ></script>
    <script src="/js/vue/vuedraggable.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

@include('meetings.agenda.create-vue')
@endpush