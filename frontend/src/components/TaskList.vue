<template>
    <v-list lines="two">
        <task-item v-for="item in items" :key="item.id" :item="item" @updated="$emit('list-updated')"></task-item>
    </v-list>
</template>

<script>
import {defineAsyncComponent} from 'vue'

const TaskItem = defineAsyncComponent(() =>
    import('./TaskItem.vue')
)

export default {
    name: "TaskList",
    components: {
        TaskItem,
    },
    props: {
        items: Array,
        loadingItems: Boolean,
    }
}
</script>

<style scoped>

</style>
