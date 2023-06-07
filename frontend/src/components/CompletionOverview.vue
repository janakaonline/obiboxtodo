<template>
    <PieChart ref="pieChart" :chartData="pieChartData"/>
</template>

<script>
import {defineComponent} from 'vue';
import {mapState} from 'vuex'
import {PieChart} from 'vue-chart-3';
import {Chart, registerables} from "chart.js";

Chart.register(...registerables);

export default defineComponent({
    components: {PieChart},
    computed: {
        ...mapState({
            message: state => state.overview.welcome,
            completedOverviewData: state => state.overview.taskCompletionData.data
        }),
        pieChartData(){
            const data = [0,0];
            if(this.completedOverviewData){
                data[0] = this.completedOverviewData.completed;
                data[1] = this.completedOverviewData.todo;
            }
            return {
                labels: ['Completed', 'Todo'],
                datasets: [
                    {
                        data: data,
                        backgroundColor: ['#4bbe03', '#af4c00'],
                    },
                ],
            }
        }
    },
    mounted() {
        this.refreshStats();
    },
    methods: {
        refreshStats() {
            this.loadCompletionChart();
        },
        async loadCompletionChart() {
            await this.$store.dispatch('overview/loadCompletionOverview')
            this.$refs.pieChart.update();
        }
    }
})
</script>

<style scoped>

</style>
