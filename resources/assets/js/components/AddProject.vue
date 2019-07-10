<template>
    <div class="sprint">
        <h4 class="card-title">Create Project</h4>
        <form method="POST" action="/sprints"
            @submit.prevent="onSubmit"
            @change="form.errors.clear($event.target.name)"
            @keydown="form.errors.clear($event.target.name)">
            <div class="form-row">
                <div class="form-group col-md-8">
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label for="project-name">Name</label>
                            <input type="test"
                                v-bind:class="['form-control', form.errors.has('name') ? 'is-invalid' : '']"
                                id="project-name"
                                name="name"
                                v-model="form.name">
                            <div class="invalid-feedback" v-if="form.errors.has('name')"
                                v-text="form.errors.get('name')"></div>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="project-cutomer">Customer</label>
                            <select
                                v-bind:class="['form-control', form.errors.has('customer') ? 'is-invalid' : '']"
                                id="project-cutomer" name="customer" v-model="form.customer">
                                <option v-for="customer in customers"
                                    :key="customer.id" v-bind:value="customer.id">
                                    {{ customer.name }}
                                </option>
                            </select>
                            <div class="invalid-feedback" v-if="form.errors.has('customer')"
                                v-text="form.errors.get('customer')"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="sprint-rate">Rate</label>
                            <input type="number" v-bind:class="['form-control', form.errors.has('rate') ? 'is-invalid' : '']" id="sprint-rate" name="rate" v-model="form.rate">
                            <div class="invalid-feedback" v-if="form.errors.has('rate')" v-text="form.errors.get('rate')"></div>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="sprint-payment-currency">Currency</label>
                            <!-- <select class="form-control" id="sprint-payment-currency" name="currency" v-model="form.currency">
                                <option value="$" selected>$</option>
                                <option value="₴">грн</option>
                            </select> -->
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
                        <div class="form-group col-md-3">
                            <label for="sprint-started">Started At</label>
                            <input type="date" class="form-control" id="sprint-started" name="started_at" v-model="form.started_at">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="project-closed-date">Archive</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox"
                                            aria-label="Project Archived" name="archived"
                                            v-model="form.archived">
                                    </div>
                                </div>
                                <input type="date" class="form-control"
                                    id="project-closed-date"
                                    name="archived_at"
                                    v-bind:disabled="!form.archived"
                                    v-model="form.archived_at">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="project-description">Description</label>
                    <b-form-textarea v-model="form.description" id="project-description"
                        placeholder="Enter description" :rows="5" :max-rows="5">
                        </b-form-textarea>

                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-2" :disabled="form.errors.any()">Create</button>

        </form>
    </div>
</template>

<script>
export default {
  props: {
    // user: Number
    //   user: Object
    //project: Number
  },
  data() {
    return {
      customers: [],
      currencies: [],
      form: new Form({
        name: { value: "", default: "" },
        started_at: { value: "", default: "" },
        description: { value: "", default: "" },
        customer: { value: "", default: "" },
        // project: {value: "this.project.id" , default: 'this.project.id'},
        rate: { value: "", default: "" },
        currency: { value: "1", default: "1" },
        rate_type: { value: "hourly", default: "hourly" },
        archived_at: { value: "", default: "" },
        archive: { value: false, default: false },
      })
    };
  },
  methods: {
    onSubmit() {
      this.form
        .submit("post", "/api/projects")
        .then(project => this.$emit("project_added", project))
        .catch(errors => console.log(errors));
    }
  },
  created() {
    axios.get("/api/customers").then(({ data }) => {
      this.customers = data;
      this.form.customer = this.customers.length > 0 ? this.customers[0].id : '';
    });

    axios.get("/api/currencies").then(({ data }) => {
      this.currencies = data;
      this.form.currency = this.currencies.length > 0 ? this.currencies[0].id : '';
    });
  }
};
</script>

