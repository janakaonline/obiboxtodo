<template>
    <main>
        <div class="stats">
            <completion-overview></completion-overview>
        </div>
    </main>
</template>

<script>
import {defineComponent} from 'vue';
import {mapState} from 'vuex'
import CompletionOverview from "../components/CompletionOverview.vue";

export default defineComponent({
    components: {CompletionOverview},
    setup() {
        return {};
    },
    computed: mapState({
        message: state => state.overview.welcome
    }),
})
</script>
