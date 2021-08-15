import ChartJS from 'vue-chartjs'

export default {
  extends: ChartJS.Bar,
  props: ['chart'],
  watch: {
    chart () {
      this.$data._chart.update()
      this.renderChart(this.chart,{
        maintainAspectRatio: false,
        scales: {
          xAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            }
          }]
        },
      })
    }
  },
  mounted () {
    this.renderChart(this.chart,{
      maintainAspectRatio: false,
      scales: {
        xAxes: [{
          ticks: {
            beginAtZero: true
          },
          gridLines: {
            display: true
          }
        }]
      },
    })
  }
}