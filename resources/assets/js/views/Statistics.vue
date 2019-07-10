<template>
    <div class="container">
        <div class="row mt-1">
            <div class="col-sm-12 my-3">
                <p class="h2">Select Period (Default month)</p>
                <rangedate-picker class=""
                  @selected="setDateRange"
                  i18n="EN"
                  v-bind:initRange="initRange"
                  :presetRanges="presetRanges"
                  ></rangedate-picker>
            </div>
            <div class="col-sm-6">
              <bar-chart-balance
                :startDate="this.form.dateStart"
                :endDate="this.form.dateEnd"
                :expense="this.expense"
                :income="this.income"
                :balance="this.balance"
              ></bar-chart-balance>
            </div>
            <div class="col-sm-6">
              <pie-chart-projects-time
                :startDate="this.form.dateStart"
                :endDate="this.form.dateEnd"
                :projects="this.projects"
              ></pie-chart-projects-time>
            </div>
            <!-- <div class="col-sm-12">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-info active">
                        <input type="radio" name="options" id="option1" autocomplete="off" checked>Month
                    </label>
                    <label class="btn btn-outline-info">
                        <input type="radio" name="options" id="option2" autocomplete="off"> 3 Monthes
                    </label>
                    <label class="btn btn-outline-info">
                        <input type="radio" name="options" id="option3" autocomplete="off"> Year
                    </label>
                </div>
            </div> -->
        </div>
    </div>
</template>

<script>
// import '../bootstrap';
Vue.use(BootstrapVue, VueRangedatePickerWinslow, moment);

// import AddProject from "../components/AddProject.vue";
// import ActiveSprints from "../components/ActiveSprints.vue";
import Preloader from "../components/Preloader.vue";
import BarChartBalance from '../charts/BarChartBalance';
import PieChartProjectsTime from '../charts/PieChartProjectsTime';

export default {
  // props: {
  //     programmers: Array
  // },
  components: {
    // "add-project": AddProject,
    // "active-sprints": ActiveSprints,
    preloader: Preloader,
    "rangedate-picker": VueRangedatePickerWinslow,
    "bar-chart-balance": BarChartBalance,
    "pie-chart-projects-time": PieChartProjectsTime
  },

  data() {
    return {
      //   showAddNew: false,
      items: [],
      // dateStart: false,
      // dateEnd: false,
      showLoader: false,
      expense: 0,
      income: 0,
      balance: 0,
      projects: [],
      form: new Form({
        dateStart: {
          value: moment().subtract(1, 'months').toDate(),
          // default: moment().subtract(1, 'months').toDate()
        },
        dateEnd: {
          value: moment().toDate(),
          // default: moment().toDate()
        },
        // notes: { value: "", default: "" }
      }),
      initRange: {
        start: moment()
          .subtract(1, 'months').add(1,'day').toDate(),
        end: moment().add(1,'day').toDate()
      },
      presetRanges: {
        thisMonth: function () {
            return {
              label: 'This Month',
              active: false,
              dateRange: {
                start: moment().startOf('month').add(1,'day').toDate(),
                end: moment().add(1,'day').toDate()
              }
            }
          },
        lastMonth: function () {
            return {
              label: 'Last Month',
              active: false,
              dateRange: {
                start: moment().subtract(1, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
        },
        last3Month: function () {
            return {
              label: 'Last 3 Month',
              active: false,
              dateRange: {
                start: moment().subtract(3, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
          },
        lastHalfYear: function () {
            return {
              label: 'Last 6 Monthes',
              active: false,
              dateRange: {
                start: moment().subtract(6, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
          },
        lastYear: function () {
            return {
              label: 'Last Year',
              active: false,
              dateRange: {
                start: moment().subtract(12, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
          },
        // last3MonthesWithCurrent: function () {
        //     return {
        //       label: 'Last 3 Monthes Include Current',
        //       active: false,
        //       dateRange: {
        //         start: moment().subtract(1, 'months').add(1,'day').toDate(),
        //         end: moment().add(1,'day').toDate()
        //       }
        //     }
        //   },
      }

    };
  },
  computed: {
    // sortOptions() {
    //   // Create an options list from our fields
    //   //   return this.fields.filter(f => f.sortable).map(f => {
    //   //     return { text: f.label, value: f.key };
    //   //   });
    // }
  },
  methods: {
    // info (item, index, button) {
    //   this.modalInfo.title = `Row index: ${index}`
    //   this.modalInfo.content = JSON.stringify(item, null, 2)
    //   this.$root.$emit('bv::show::modal', 'modalInfo', button)
    // },
    setDateRange(dates) {
      //console.log(dates);
      this.form.dateStart = moment(dates.start).subtract(1, 'days').toDate();
      this.form.dateEnd = moment(dates.end).subtract(1, 'days').toDate();
      this.getStatsForPeriod();
    },
    moment: function() {
      return moment();
    },
    getStatsForPeriod: function() {
      // axios
      //   .post("/api/statistics")
      //   .then(({ data }) => {
      //     this.items = data;
      //     // this.totalRows = data.length;
      //   })
      //   .then(() => (this.showLoader = false));

      this.form
        .post("/api/statistics")
        .then(statistics => {
          this.expense = parseFloat(statistics.expense);
          this.income = parseFloat (statistics.income);
          this.balance = parseFloat (statistics.balance);
          this.projects = statistics.projects;
        })
        .catch(errors => console.log(errors));
    }
  },
  created() {
    //make an ajax request to our server
    this.showLoader = true;
    // this.dateStartdateStart = moment().subtract(1, 'months').toDate();
    // this.dateEnd = moment().toDate();
    this.getStatsForPeriod();

    // axios
    //   .get("/api/projects")
    //   .then(({ data }) => {
    //     this.items = data;
    //     // this.totalRows = data.length;
    //   })
    //   .then(() => (this.showLoader = false));
  },
};
</script>

<!-- table-complete-1.vue -->