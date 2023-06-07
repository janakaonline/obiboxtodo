<template>
    <v-card>
        <v-card-title>Tasks Completion Overview</v-card-title>
        <v-card-text>
            <PieChart ref="chart" :chartData="chartData"/>
        </v-card-text>
    </v-card>
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
        chartData(){
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
        async refreshStats() {
            await this.$store.dispatch('overview/loadCompletionOverview')
            this.$refs.chart.update();
        }
    }
})
</script>

<style scoped>

</style>
