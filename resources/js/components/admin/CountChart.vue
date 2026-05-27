<template>
    <div class="card h-100">
        <div class="card-header bg-light py-2">
            <div class="row flex-between-center">
                <div class="col-auto">
                    <h6 class="mb-0">{{ title }}</h6>
                </div>
                <div class="col-4 d-flex">
                   <select class="form-control text-center" v-model="type">
                       <option value="weekly">هفتگی</option>
                       <option value="monthly">ماهانه</option>
                       <option value="yearly">سالانه</option>
                   </select>
                   <select class="form-control text-center" v-model="count">
                       <template v-for="i in max_count">
                           <option v-if="i !== 1" :key="i" :value="i">{{ i }}</option>
                       </template>
                   </select>
                </div>
            </div>
        </div>
        <div class="card-body h-100">
            <highcharts :options="options" ref="countChart"></highcharts>
        </div>
    </div>
</template>

<script>
export default {
    name: "CountChart",
    props: ['route', 'title'],
    data() {
        return {
            max_count: 24,
            count: 12,
            type: 'weekly',
            options: {
                chart: {
                    type: 'column',
                    zoomType: 'x',
                    backgroundColor: 'var(--falcon-card-bg)',
                },
                colors: ['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'],
                title: {
                    text: ''
                },
                xAxis: {
                    categories: [],
                    labels: {
                        style: {
                            color: '#5e6e82',
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'تعداد',
                        style: {
                            color: '#5e6e82',
                        }
                    },
                    labels: {
                        style: {
                            color: '#5e6e82',
                        }
                    },
                    gridLineColor: 'rgba(94, 110, 130, 0.2)',
                },
                series: [
                    {
                        name: 'تعداد',
                        colorByPoint: true,
                        data: [],
                        dataLabels: {
                            enabled: true,
                            style: {
                                color: '#344050',
                            }
                        }
                    }
                ]
            }
        }
    },
    mounted() {
        this.getData()
    },
    watch: {
        type: function(type) {
            this.getData();

            if (type === 'weekly'){
                this.max_count = 24;
                this.count = 12;
            }
            if (type === 'monthly'){
                this.max_count = 12;
                this.count = 6;
            }
            if (type === 'yearly'){
                this.max_count = 6;
                this.count = 3;
            }
        },
        count: function() {
            this.getData()
        },
    },
    methods: {
        getData() {
            axios.get(this.route, {
                params: {
                    count: this.count,
                    type: this.type,
                }
            })
            .then((response) => {
                const categories = []
                const data = []

                response.data.forEach((item) => {
                    categories.push(item.date)
                    data.push(item.count)
                })

                this.options = {
                    ...this.options,
                    xAxis: {
                        ...this.options.xAxis,
                        categories,
                    },
                    series: [
                        {
                            ...this.options.series[0],
                            data,
                        },
                    ],
                }
            })
            .catch((error) => {
                console.error('CountChart', error)
            })
        }
    }
}
</script>

<style scoped>

</style>
