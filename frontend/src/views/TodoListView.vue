<template>
    <main>
        <Suspense>
            <task-list/>
        </Suspense>
    </main>
</template>

<script>
import {defineAsyncComponent} from 'vue'

const TaskList = defineAsyncComponent(() =>
    import('../components/TaskList.vue')
)
export default {
    components: {
        TaskList
    },
}
</script>
