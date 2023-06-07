<template>
    <PieChart :chartData="testData" />
</template>

<script>
import { defineComponent  } from 'vue';
import {mapState} from 'vuex'
import {PieChart} from 'vue-chart-3';
import {Chart, registerables} from "chart.js";

Chart.register(...registerables);

export default defineComponent({
    components: {PieChart},
    setup(){
        const completionOverviewData = await this.$store.dispatch('overview/loadCompletionOverview')
        console.log(completionOverviewData)
        const testData = {
            labels: ['Paris', 'Nîmes', 'Toulon', 'Perpignan', 'Autre'],
            datasets: [
                {
                    data: [30, 40, 60, 70, 5],
                    backgroundColor: ['#77CEFF', '#0079AF', '#123E6B', '#97B0C4', '#A5C8ED'],
                },
            ],
        };

        return { testData };
    },
    computed: mapState({
        message: state => state.overview.welcome
    }),
    methods:{
        async loadCompletionChart(){
            const completionOverviewData = await this.$store.dispatch('overview/loadCompletionOverview')
        }
    }
})
</script>

<style scoped>

</style>
