<template>
    <div class="sprint">
        <h4 class="card-title">Finish Sprint</h4>
        <form method="POST" action="/sprints" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)" @change="form.errors.clear($event.target.name)">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="sprint-project">Project</label>
                    <select disabled v-bind:class="['form-control', form.errors.has('project') ? 'is-invalid' : '']" id="sprint-project" name="project" @change="setSprintRate($event.target.value)" v-model="form.project">
                        <option v-for="project in projects" :key="project.id" v-bind:value="project.id">
                            {{ project.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="form.errors.has('project')" v-text="form.errors.get('project')"></div>
                </div>
                <div class="form-group col-md-2">
                    <label for="sprint-rate">Rate</label>
                    <input type="number" v-bind:class="['form-control', form.errors.has('rate') ? 'is-invalid' : '']" id="sprint-rate" name="rate" v-model="form.rate">
                    <div class="invalid-feedback" v-if="form.errors.has('rate')" v-text="form.errors.get('rate')"></div>
                </div>
                <div class="form-group col-md-1">
                    <label for="sprint-payment-currency">Currency</label>
                    <select class="form-control" id="sprint-payment-currency" name="currency" v-model="form.currency">
                        <option v-for="currency in currencies"
                            :key="currency.id" v-bind:value="currency.id">
                            {{ currency.symbol }}
                        </option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="sprint-rate-type">Rate Type</label>
                    <select class="form-control" id="sprint-rate-type" name="rate_type" v-model="form.rate_type">
                        <option value="hourly" selected>Horly</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="sprint-started">Started At</label>
                    <input type="date" class="form-control"
                        id="sprint-started" name="started_at"
                        v-model="form.started_at">
                </div>
                <div class="form-group col-md-2">
                    <label for="sprint-worked-time">Time Worked</label>
                    <input type="number" class="form-control" id="worked-time" name="worked_time" v-model="form.worked_time">
                </div>
                <div class="form-group col-md-3">
                    <label for="sprint-closed-date">Close</label>
                    <input type="date" class="form-control" id="sprint-closed-date" name="closed_date" v-model="form.closed_date">
                </div>
                <div class="form-group col-md-2">
                    <label for="sprint-payment-status">Payment Status</label>
                    <select class="form-control" id="sprint-payment-status" name="payment_status" v-model="form.payment_status">
                        <option value="1" selected>Not Paid</option>
                        <option value="2">Paid</option>
                    </select>
                </div>
            </div>
            <b-button @click.stop="$emit('cancel_finish_sprint')" class="btn btn-primary mb-2">
                Cancel
            </b-button>
            <button type="submit" class="btn btn-success mb-2" :disabled="form.errors.any()">Finish Sprint</button>
        </form>
    </div>
</template>

<script>
import moment from "moment";

export default {
  props: {
    sprint: Object
  },
  data() {
    //  console.log('wtf');
    return {
      projects: [],
      currencies: [],
    };
  },
  computed: {
    form: function() {
      return new Form({
        sprint_id: { value: this.sprint.id, default: this.sprint.id },
        user_id: { value: this.sprint.user_id, default: this.sprint.user_id },
        project: {
          value: this.sprint.project.id,
          default: this.sprint.project.id
        },
        // project: {value: "this.project.id" , default: 'this.project.id'},
        rate: { value: this.sprint.rate, default: this.sprint.rate },
        currency: {
          value: this.sprint.currency.id,
          default: this.sprint.currency.id
        },
        rate_type: {
          value: this.sprint.rate_type,
          default: this.sprint.rate_type
        },
        started_at: { value: moment(this.sprint.started_at).format('YYYY-MM-DD'), default: "" },
        worked_time: {
          value: this.sprint.worked_time ? this.sprint.worked_time : 0,
          default: ""
        },
        closed: { value: true, default: true },
        closed_date: { value: moment().format('YYYY-MM-DD'), default: moment().format('YYYY-MM-DD') },
        payment_status: { value: "2", default: "2" }
      });
    }
  },
  methods: {
    setSprintRate(projectId) {
      var project = this.projects.find(item => item.id == projectId);
      this.form.rate = project.rate ? project.rate : "";
      this.form.currency = project.currency.id ? project.currency.id : "1";
      this.form.rate_type = project.rate_type ? project.rate_type : "";
    },
    onSubmit() {
      this.form
        .submit("patch", "/api/sprints")
        .then(data => this.$emit("sprint_finished", data))
        .catch(errors => console.log(errors));
    }
  },
  created() {
    axios.get("/api/currencies").then(({ data }) => {
      this.currencies = data;
      //this.form.currency = this.currencies.length > 0 ? this.currencies[0].id : '';
    });

    axios.get("/api/projects").then(({ data }) => {
      this.projects = data;
    });
  }
};
</script>

