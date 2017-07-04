<template>
    <div class="row">
        <div v-for="list in lists" class="col-md-4 col-sm-6 col-xs-12">
            <div class="boxs">
                <div class="boxs-header">
                    <h1 class="custom-font"> {{ list.name }} </h1>
                </div>
                <div class="boxs-body">
                    <div class="list-group">
                        <div v-for="task in list.tasks" class="list-group-item">
                            {{ task.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>