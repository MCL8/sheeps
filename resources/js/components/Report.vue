<template>
    <div class="container">
        <ul class="list-group">
            <li class="list-group-item" v-for="report in reports" :key="report.day">
                <b>День {{ report.day }}</b> <br>
                всего овечек: {{ report.sheeps_alive}} <br>
                убитых овечек: {{ report.sheeps_dead}} <br>
                живых овечек: {{ report.sheeps_alive}} <br>
                наиболее населенный загон: {{ report.corral_max}} <br>
                наименее населенный загон: {{ report.corral_min}} <br><br>
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios';
    import r from '../route';

    export default {
        data() {
            return {
                reports: []
            }
        },
        methods: {
            getReports() {
                axios.get(r('reports.index'))
                    .then((response) => {
                        this.reports = response.data;
                        console.log(this.reports);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        mounted() {
            this.getReports();
        }
    }
</script>

