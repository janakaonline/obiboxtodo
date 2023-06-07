<template>
    <main>
        <div class="stats">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="6" >
                        <completion-overview ref="completionOverview"></completion-overview>
                    </v-col>
                    <v-col cols="12" sm="6" >
                        <tasks-by-priority-overview ref="priorityOverview"></tasks-by-priority-overview>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-tabs v-model="tab" bg-color="primary">
                                <v-tab value="dueToday">Due today</v-tab>
                                <v-tab value="overDue">Overdue</v-tab>
                            </v-tabs>

                            <v-card-text>
                                <v-window v-model="tab">
                                    <v-window-item value="dueToday" class="pt-5">
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
                                        </div>
                                        <task-list :items="todoItems" :loading-items="todoLoadingItems" @list-updated="refreshLists"/>
                                    </v-window-item>

                                    <v-window-item value="overDue" class="pt-5">
                                        <div class="sort-options">
                                            <v-select
                                                label="Priority"
                                                density="compact"
                                                :items="overdueSortOptions.priority.options"
                                                item-title="name"
                                                item-value="value"
                                                variant="outlined"
                                                v-model="overdueSortOptions.priority.value"
                                                @update:modelValue="refreshOverdueList"
                                            ></v-select>
                                        </div>
                                        <task-list :items="overdueItems" :loading-items="overdueLoadingItems" @list-updated="refreshLists"/>
                                    </v-window-item>
                                </v-window>
                            </v-card-text>
                        </v-card>


                    </v-col>
                </v-row>
            </v-container>


        </div>
    </main>
</template>

<script>
import {defineComponent} from 'vue';
import {mapState} from 'vuex'
import CompletionOverview from "../components/CompletionOverview.vue";
import TasksByPriorityOverview from "../components/TasksByPriorityOverview.vue";
import TaskList from '../components/TaskList.vue'

export default defineComponent({
    components: {CompletionOverview, TasksByPriorityOverview, TaskList},
    computed: mapState({
        todoItems: state => state.todoList.todoTasks.items,
        todoLoadingItems: state => state.todoList.todoTasks.loadingItems,
        overdueItems: state => state.todoList.overdueTasks.items,
        overdueLoadingItems: state => state.todoList.overdueTasks.loadingItems,
    }),
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
                }
            },
            overdueSortOptions: {
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
                }
            }
        }
    },
    mounted() {
        this.refreshLists();
    },
    methods:{
        refreshLists(){
            this.refreshTodoList();
            this.refreshOverdueList();
            this.$refs.completionOverview.refreshStats();
            this.$refs.priorityOverview.refreshStats();
        },
        refreshTodoList() {
            const filters = {
                sort: [
                    {field: 'priority', 'order': this.todoSortOptions.priority.value},
                ]
            }
            this.$store.dispatch('todoList/loadTodoTasks', filters)
        },
        refreshOverdueList() {
            const filters = {
                sort: [
                    {field: 'priority', 'order': this.overdueSortOptions.priority.value},
                ]
            }
            this.$store.dispatch('todoList/loadOverdueTasks', filters)
        }
    }
})
</script>
