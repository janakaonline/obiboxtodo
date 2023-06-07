<template>
    <main>

        <v-container>
            <v-row>
                <v-col>
                    <div class="d-flex justify-end">
                        <v-btn class="ma-1"
                               color="primary"
                               prepend-icon="mdi-plus-circle"
                               @click="openCreateNewDialog"
                        >Create Task
                        </v-btn>
                    </div>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <v-card>
                        <v-tabs v-model="tab" bg-color="primary">
                            <v-tab value="todo">To do</v-tab>
                            <v-tab value="completed">Completed</v-tab>
                        </v-tabs>

                        <v-card-text>
                            <v-window v-model="tab">
                                <v-window-item value="todo" class="pt-5">
                                    <div class="sort-options">
                                        <v-select
                                            label="Priority"
                                            density="compact"
                                            :items="todoSortOptions.priority.options"
                                            item-title="name"
                                            item-value="value"
                                            variant="outlined"
                                            v-model="todoSortOptions.priority.value"
                                            @update:modelValue="refreshTodoList"
                                        ></v-select>
                                        <v-select
                                            label="Due Date"
                                            density="compact"
                                            :items="todoSortOptions.due_date.options"
                                            item-title="name"
                                            item-value="value"
                                            variant="outlined"
                                            v-model="todoSortOptions.due_date.value"
                                            @update:modelValue="refreshTodoList"
                                        ></v-select>
                                    </div>
                                    <task-list :items="items" :loading-items="loadingItems" @list-updated="refreshLists"/>
                                </v-window-item>

                                <v-window-item value="completed" class="pt-5">
                                    <div class="sort-options">
                                        <v-select
                                            label="Priority"
                                            density="compact"
                                            :items="completedSortOptions.priority.options"
                                            item-title="name"
                                            item-value="value"
                                            variant="outlined"
                                            v-model="completedSortOptions.priority.value"
                                            @update:modelValue="refreshCompletedList"
                                        ></v-select>
                                        <v-select
                                            label="Due Date"
                                            density="compact"
                                            :items="completedSortOptions.due_date.options"
                                            item-title="name"
                                            item-value="value"
                                            variant="outlined"
                                            v-model="completedSortOptions.due_date.value"
                                            @update:modelValue="refreshCompletedList"
                                        ></v-select>
                                    </div>
                                    <task-list :items="completedItems" :loading-items="loadingCompletedItems" @list-updated="refreshLists"/>
                                </v-window-item>
                            </v-window>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>



        <task-save-dialog @submit="addTask" ref="newTaskDialog"></task-save-dialog>
    </main>
</template>

<script>
import TaskList from '../components/TaskList.vue'
import TaskSaveDialog from "../components/TaskSaveDialog.vue";
import {mapState} from "vuex";

export default {
    components: {
        TaskList,
        TaskSaveDialog
    },
    data() {
        return {
            tab: null,
            todoSortOptions: {
                priority: {
                    options: [
                        {
                            name: 'Any',
                            value: '',
                        },
                        {
                            name: 'High to Low',
                            value: 'desc',
                        },
                        {
                            name: 'Low to High',
                            value: 'asc',
                        },
                    ],
                    value: 'desc'
                },
                due_date: {
                    options: [
                        {
                            name: 'Any',
                            value: '',
                        },
                        {
                            name: 'Descending',
                            value: 'desc',
                        },
                        {
                            name: 'Ascending',
                            value: 'asc',
                        },
                    ],
                    value: 'desc'
                },
            },
            completedSortOptions: {
                priority: {
                    options: [
                        {
                            name: 'Any',
                            value: '',
                        },
                        {
                            name: 'High to Low',
                            value: 'desc',
                        },
                        {
                            name: 'Low to High',
                            value: 'asc',
                        },
                    ],
                    value: ''
                },
                due_date: {
                    options: [
                        {
                            name: 'Any',
                            value: '',
                        },
                        {
                            name: 'Descending',
                            value: 'desc',
                        },
                        {
                            name: 'Ascending',
                            value: 'asc',
                        },
                    ],
                    value: 'desc'
                },
            }
        }
    },
    computed: mapState({
        items: state => state.todoList.items,
        loadingItems: state => state.todoList.loadingItems,
        completedItems: state => state.todoList.completedTasks.items,
        loadingCompletedItems: state => state.todoList.completedTasks.loadingItems,
    }),
    mounted() {
        this.refreshLists();
    },
    methods: {
        openCreateNewDialog() {
            const newItem = {
                priority: 'high'
            };
            this.$refs.newTaskDialog.open(newItem);
        },
        addTask(task) {
            this.$store.dispatch('todoList/add', task)
                .then(() => {
                    this.$refs.newTaskDialog.close();
                    this.refreshLists();
                })
        },
        refreshLists(){
            this.refreshTodoList()
            this.refreshCompletedList()
        },
        refreshTodoList() {
            const filters = {
                filter: [
                    {field: 'completed', 'value': false},
                ],
                sort: [
                    {field: 'priority', 'order': this.todoSortOptions.priority.value},
                    {field: 'due_date', 'order': this.todoSortOptions.due_date.value},
                ]
            }
            this.$store.dispatch('todoList/loadTasks', filters)
        },

        refreshCompletedList() {
            const filters = {
                filter: [
                    {field: 'completed', 'value': true},
                ],
                sort: [
                    {field: 'priority', 'order': this.completedSortOptions.priority.value},
                    {field: 'due_date', 'order': this.completedSortOptions.due_date.value},
                ]
            }
            this.$store.dispatch('todoList/loadCompletedTasks', filters)
        }
    }
}
</script>
