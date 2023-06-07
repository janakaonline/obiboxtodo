<template>
    <PieChart ref="chart" :chartData="chartData"/>
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
            tasksByPriorityData: state => state.overview.tasksByPriorityData.data
        }),
        chartData(){
            const data = [0,0,0];
            if(this.tasksByPriorityData){
                data[0] = this.tasksByPriorityData.high;
                data[1] = this.tasksByPriorityData.medium;
                data[2] = this.tasksByPriorityData.low;
            }
            return {
                labels: ['High', 'Medium', 'Low'],
                datasets: [
                    {
                        data: data,
                        backgroundColor: ['#af0f00', '#d68d0e', '#7c7e6a'],
                    },
                ],
            }
        }
    },
    mounted() {
        this.loadChart();
    },
    methods: {
        async loadChart() {
            await this.$store.dispatch('overview/loadTasksByPriorityOverview')
            this.$refs.chart.update();
        }
    }
})
</script>

<style scoped>

</style>
