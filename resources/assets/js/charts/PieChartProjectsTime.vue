<template>
    <div>
        <highcharts :options="this.chartData" ref="highcharts"></highcharts>
    </div>
</template>

<script>
import HighchartsVue from "highcharts-vue";
import Chart from "./Chart.vue";
import StockChart from "./StockChart";

Vue.use(HighchartsVue);

export default {
  components: {
    // highcharts: HighchartsVue,
    chart: Chart,
    stockChart: StockChart
  },
  props: {
    startDate: Date,
    endDate: Date,
    projects: Array,
  },
  data() {
    return {};
  },
  computed: {
    projectsData: function() {
        return this.projects.length > 0 ?
            this.projects.map(function(item){
                return {
                    name: item.project,
                    y: item.time,
                    drilldown: item.customer,
                    customer: item.customer,
                    total: item.total
                }
            })
            : [
              {
                name: "No Projects",
                y: 0,
                drilldown: "No Projects"
              }
            ];
    },
    chartData: function() {
      let startDate = moment(this.startDate).format('MMM Do YY');
      let endDate = moment(this.endDate).format('MMM Do YY');

      return {
        chart: {
          type: "pie"
        },
        title: {
          text: `Time spent on projects. Current sprints and closed at ${startDate}-${endDate}`
        },
        plotOptions: {
          series: {
            dataLabels: {
              enabled: true,
              format: "{point.name}: {point.y} hours"
            }
          }
        },

        tooltip: {
          headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
          pointFormat:
            '<span style="color:{point.color}">{point.customer}</span>: <b>{point.y}h</b> of total: <b>{point.total}h<b><br/>'
        },

        series: [
          {
            name: "Projects",
            colorByPoint: true,
            data: this.projectsData,
          }
        ]
      };
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