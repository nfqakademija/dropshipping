<template>
    <div>
        <area-chart
                id="area" :data="areaData" xkey="year" ykeys='[ "a", "b" ]' resize="true"
                labels='[ "Serie A", "Serie B" ]' line-colors='[ "#FF6384", "#36A2EB" ]'
                grid="true" grid-text-weight="bold" x-label-angle="45">
        </area-chart>

<!--        <line-chart-->
<!--                id="line" xkey="year" resize="true"-->
<!--                :labels="labels"-->
<!--                :data="lineData"-->
<!--                :ykeys="series"-->
<!--                _line-colors="lineColors"-->
<!--                :line-colors="lineColor"-->
<!--                :hover-callback="onLineHover"-->
<!--                grid="true" grid-text-weight="bold" events='[ "2010 " ]' event-stroke-width="5" event-line-colors='[ "#ff0000" ]'>-->
<!--        </line-chart>-->
    </div>
</template>
<script>
    import Raphael from 'raphael/raphael';
    global.Raphael = Raphael;
    import { LineChart } from 'vue-morris';
    import { AreaChart } from 'vue-morris';

    const COLORS = [ '#42B8E0', '#33658A', '#F6AE2D', '#F26419', '#0E3A53' ]

    export default {
        components: {
            LineChart, AreaChart
        },
        data() {
            return {
                lineData: [],
                areaData: [],

                series: [ 'a', 'b' ],
                labels: [ 'Serie A', 'Serie B' ],
                lineColors: [ COLORS[0], COLORS[1] ],
            }
        },

        mounted () {
            setInterval(() => {

                this.donutData = [
                    { label: 'Red', value: 300 },
                    { label: 'Blue', value: 50 },
                    { label: 'Yellow', value: 100 }
                ]

                this.barData = [
                    { year: '2013', and: 10, ios: 5, win: 2 },
                    { year: '2014', and: 10, ios: 15, win: 3 },
                    { year: '2015', and: 20, ios: 25, win: 2 },
                    { year: '2016', and: 30, ios: 20, win: 1 },
                ]

                this.areaData = [
                    { year: '2013', a: 30, b: 5 },
                    { year: '2014', a: 25, b: 15 },
                    { year: '2015', a: 29, b: 25 },
                    { year: '2016', a: 50, b: 20 },
                ]



                const years = []

                this.series = []
                this.labels = []
                this.lineColors = []
                for (let i = 0; i < this.rand(4) + 1; i++) {
                    this.series.push(String.fromCharCode(i + 97))
                    this.labels.push('Serie ' + String.fromCharCode(i + 65))
                    this.lineColors.push(COLORS[i])
                }

                this.lineData = []

                const nbYears = this.rand(4) + 2

                for (let i = 0; i < nbYears; i++) {
                    let year = 0
                    do {
                        year = this.rand(18) + 2000;
                    }
                    while (years.includes(year));

                    years.push(year)

                    const data = { year: '' + year };

                    for (let serie in this.series) {
                        data[this.series[serie]] = this.rand(100)
                    }

                    this.lineData.push(data)
                }

                console.log('Nb years', nbYears)
                console.log('Series', this.series)
                /*this.lineData = [
                  { year: '' + (this.rand(18) + 2000), a: this.rand(100), b: this.rand(100) },
                  { year: '' + (this.rand(18) + 2000), a: this.rand(100), b: this.rand(100) },
                  { year: '' + (this.rand(18) + 2000), a: this.rand(100), b: this.rand(100) },
                  { year: '' + (this.rand(18) + 2000), a: this.rand(100), b: this.rand(100) },
                ]*/
            }, 5000)
        },

        methods: {
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