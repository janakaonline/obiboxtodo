<template>
    <v-list lines="two">
        <task-item v-for="item in items" :key="item.id" :item="item" @updated="loadTasks"></task-item>
    </v-list>
</template>

<script>
import {mapState} from "vuex";
import TaskItem from "../components/TaskItem.vue";

export default {
    name: "TaskList",
    components: {
        TaskItem
    },
    computed: mapState({
        items: state => state.todoList.items,
    }),
    mounted() {
        this.loadTasks();
    },
    methods: {
        loadTasks() {
            this.$store.dispatch('todoList/loadTasks')
        }
    }
}
</script>

<style scoped>

</style>
