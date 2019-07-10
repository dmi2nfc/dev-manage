<template>
  <div class="sprint">
    <h4 class="card-title">Create Sprint</h4>
    <form method="POST" action="/sprints"
      @submit.prevent="onSubmit"
      @keydown="form.errors.clear($event.target.name)"
      @change="form.errors.clear($event.target.name)">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="sprint-project">Project</label>
          <select
              v-bind:class="['form-control', form.errors.has('project') ? 'is-invalid' : '']"
              id="sprint-project"
              name="project"
              @change="setSprintRate($event.target.value)"
              v-model="form.project">
              <option v-for="project in projects"
                  :key="project.id" v-bind:value="project.id">
                  {{ project.name }}
              </option>
          </select>
          <div class="invalid-feedback" v-if="form.errors.has('project')"
               v-text="form.errors.get('project')"></div>
        </div>
        <div class="form-group col-md-2">
          <label for="sprint-rate">Rate</label>
          <input type="number"
            v-bind:class="['form-control', form.errors.has('rate') ? 'is-invalid' : '']"
            id="sprint-rate" name="rate" v-model="form.rate">
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
          <input type="date" class="form-control" id="sprint-started" name="started_at" v-model="form.started_at">
        </div>
        <div class="form-group col-md-2">
          <label for="sprint-worked-time">Time Worked</label>
          <input type="number" min="0" class="form-control" id="worked-time" name="worked_time" v-model="form.worked_time">
        </div>
        <div class="form-group col-md-3">
          <label for="sprint-closed-date">Close</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <input type="checkbox" aria-label="Sprint Closed" name="closed" v-model="form.closed">
              </div>
            </div>
            <input type="date" class="form-control" id="sprint-closed-date" name="closed_date" v-bind:disabled="!form.closed" v-model="form.closed_date">
          </div>
        </div>
        <div class="form-group col-md-2">
          <label for="sprint-payment-status">Payment Status</label>
          <select class="form-control" id="sprint-payment-status" name="payment_status" v-model="form.payment_status">
            <option value="1" selected>Not Paid</option>
            <option value="2">Paid</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mb-2" :disabled="form.errors.any()">Create</button>

    </form>
  </div>
</template>

<script>

export default {
  props: {
    user: Number
    //   user: Object
    //project: Number
  },
  data() {
    return {
      form: new Form({
        user_id: { value: this.user, default: this.user },
        project: { value: "", default: "" },
        // project: {value: "this.project.id" , default: 'this.project.id'},
        rate: { value: "", default: "" },
        currency: { value: "1", default: "1" },
        rate_type: { value: "hourly", default: "hourly" },
        started_at: { value: "", default: "" },
        worked_time: { value: "0", default: "0" },
        closed: { value: false, default: false },
        closed_date: { value: "", default: "" },
        payment_status: { value: "1", default: "1" }
      }),
      projects: [],
      currencies: [],
    };
  },
  methods: {
    setSprintRate(projectId) {
      var project = this.projects.find(item => item.id == projectId);
      this.form.rate = project.rate ? project.rate : '';
      this.form.currency = project.currency ? project.currency.id : '1';
      this.form.rate_type = project.rate_type  ? project.rate_type : '';

    },
    onSubmit() {
      this.form
        .submit("post", "/api/sprints")
        .then(sprint => this.$emit('sprint_added', sprint))
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
      this.form.project = this.projects.length > 0 ? this.projects[0].id : '';
      this.form.rate = this.projects.length > 0 ? this.projects[0].rate : '';
      this.form.currency = this.projects.length > 0 ? this.projects[0].currency.id : '1';
      this.form.rate_type = this.projects.length > 0 ? this.projects[0].rate_type : '';
    });
  }
};
</script>

