<template>
    <div>
        <line-chart
                id="line" xkey="month" resize="true" pointFillColors='[ "#7867A7" ]' lineColors='[ "#9685c6" ]'
                :labels="labels"
                :data="lineData"
                :ykeys="series"
                :hover-callback="onLineHover"
                grid="true" grid-text-weight="bold" event-stroke-width="5" event-line-colors='[ "#ff0000" ]'>
        </line-chart>
    </div>
</template>
<script>
    import Raphael from 'raphael/raphael';
    import moment from 'moment';
    global.Raphael = Raphael;
    import { LineChart } from 'vue-morris';
    const COLORS = [ '#42B8E0', '#33658A', '#F6AE2D', '#F26419', '#0E3A53' ]
    export default {
        components: {
            LineChart
        },
        data() {
            return {
                lineData: [],
                origs: '',
                series: [ 'a', 'b' ],
                labels: [ 'Orders:' ],
                lineColors: [ COLORS[0], COLORS[1] ],
            }
        },

        mounted () {
            this.getGraph()
        },

        methods: {
            getGraph() {
                axios.get('/api/ebay-generate').then( (response) => {
                    this.origs = response.data
                    console.log(this.origs)
                    this.series = []
                    this.labels = []
                    this.lineColors = []
                    for (let i = 0; i < this.rand(4) + 1; i++) {
                        this.series.push(String.fromCharCode(i + 97))
                        this.labels.push('Orders')
                        this.lineColors.push(COLORS[i])
                    }

                    this.lineData = []
                    for(let x = 0; x < 31; x++) {
                        let dax = { month: this.origs['dates'+x]['dates'], a: this.origs['dates'+x]['values'] }
                        this.lineData.push(dax)
                    }
                });
            },
            rand (limit) {
                return Math.ceil(Math.random() * limit)
            },

            onLineHover (index, options, content, row) {
                // console.log('onLineHover: ', index, options, content, row)
                return content
            },

            osColor (row, series, type) {
                //console.log(row.y, series.key)

                if (series.key === 'and') {
                    if (row.y >= 30) return '#FF6384'
                    if (row.y >= 15) return '#CC6384'
                    return '#996384'
                }

                if (series.key === 'ios') {
                    return '#36A2EB'
                }

                return '#FFCE56'
            },

            percentFormat (val) {
                return val + '%'
            },

            lineColor (row, series, type) {
                //console.log(row, series, type)

                if (type === 'point') {
                    if (row.y[series] < 10) {
                        return '#F00'
                    }
                }

                return this.lineColors[series]
            }
        }
    }
</script>