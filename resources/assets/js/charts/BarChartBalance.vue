<template>
    <div>
        <highcharts :options="this.chartData" ref="highcharts"></highcharts>
    </div>
</template>

<script>
import HighchartsVue from 'highcharts-vue'
import Chart from './Chart.vue'
import StockChart from './StockChart'

Vue.use(HighchartsVue)


export default {
  components: {
    // highcharts: HighchartsVue,
    chart: Chart,
    stockChart: StockChart
  },
  props: {
    startDate: Date,
    endDate: Date,
    expense: Number,
    income: Number,
    balance: Number
  },
  data() {
    return {
    };
  },
  computed: {
    chartData: function() {
      let startDate = moment(this.startDate).format('MMM Do YY');
      let endDate = moment(this.endDate).format('MMM Do YY');
      return {
            chart: {
                type: 'column'
            },
            title: {
                text: `Balance: ${this.balance}$`
            },
            xAxis: {
                categories: [`${startDate}-${endDate}`]
            },
            yAxis: {
                 title:{
                    text: "$",
                    rotation: 0,
                    align: "high"
                 }
            },
            credits: {
                enabled: false
            },
            series: [{
                    name: 'Income',
                    data: [this.income],
                    valueSuffix: '$',
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}$'
                    }
                }, {
                    name: 'Expense',
                    data: [this.expense],
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}$'
                    }
                }
            ],
            colors: ['green', 'red']
       }
    }
  },
  methods: {
    // onSubmit() {
    //   this.form
    //     .submit("post", "/api/projects")
    //     .then(project => this.$emit("project_added", project))
    //     .catch(errors => console.log(errors));
    // }
  },
  created() {
    //   console.log(this.startDate);
    //console.log('wtf');
    // axios.get("/api/customers").then(({ data }) => {
    //   this.customers = data;
    //   this.form.customer = this.customers.length > 0 ? this.customers[0].id : '';
    // });
  }
};
</script>