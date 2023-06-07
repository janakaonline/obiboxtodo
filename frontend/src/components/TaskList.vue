<template>
    <div v-if="loadingItems">Loading</div>
    <div v-if="!loadingItems">
        <div class="sort-options">
            <v-select
                label="Priority"
                density="compact"
                :items="sortOptions.priority.options"
                item-title="name"
                item-value="value"
                variant="outlined"
                v-model="sortOptions.priority.value"
                @update:modelValue="loadTasks"
            ></v-select>
            <v-select
                label="Due Date"
                density="compact"
                :items="sortOptions.due_date.options"
                item-title="name"
                item-value="value"
                variant="outlined"
                v-model="sortOptions.due_date.value"
                @update:modelValue="loadTasks"
            ></v-select>
        </div>

        <div class="d-flex justify-end">
            <v-btn class="ma-1"
                   color="primary"
                   prepend-icon="mdi-plus-circle"
                   @click="openCreateNewDialog"
            >Create Task
            </v-btn>
        </div>
        <!--<ui>
            <li v-for="item in items">{{item.name}}</li>
        </ui>-->
        <v-list lines="two">
            <Suspense v-for="item in items" :key="item.id">
                <task-item :item="item" @updated="loadTasks"></task-item>
            </Suspense>
        </v-list>
        <Suspense v-for="item in items" :key="item.id">
            <task-save-dialog @submit="addTask" ref="newTaskDialog"></task-save-dialog>
        </Suspense>
    </div>
</template>

<script>
import {mapState} from "vuex";
import {defineAsyncComponent} from 'vue'

const TaskItem = defineAsyncComponent(() =>
    import('./TaskItem.vue')
)
const TaskSaveDialog = defineAsyncComponent(() =>
    import('./TaskSaveDialog.vue')
)

export default {
    name: "TaskList",
    components: {
        TaskItem,
        TaskSaveDialog
    },
    data() {
        return {
            sortOptions: {
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
                    value: ''
                },
            }
        }
    },
    computed: mapState({
        items: state => state.todoList.items,
        loadingItems: state => state.todoList.loadingItems,
    }),
    mounted() {
        this.loadTasks();
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
                    this.loadTasks();
                })
        },
        loadTasks() {
            this.$store.dispatch('todoList/loadTasks', {
                filter: [
                    {field: 'completed', 'value': false},
                ],
                sort: [
                    {field: 'priority', 'order': this.sortOptions.priority.value},
                    {field: 'due_date', 'order': this.sortOptions.due_date.value},
                ]
            })
        }
    }
}
</script>

<style scoped>

</style>
