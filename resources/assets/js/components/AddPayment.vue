<template>
    <div class="sprint">
        <h4 class="card-title">Add Payment</h4>
        <form method="POST" action="/sprints"
            @submit.prevent="onSubmit"
            @change="form.errors.clear($event.target.name)"
            @keydown="form.errors.clear($event.target.name)">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="transaction-amount">Amount</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                        <input type="number"
                            v-bind:class="['form-control', form.errors.has('amount') ? 'is-invalid' : '']"
                            id="transaction-amount"
                            name="amount"
                            v-model="form.amount">
                        <div class="invalid-feedback" v-if="form.errors.has('amount')"
                            v-text="form.errors.get('amount')"></div>
                        </div>

                </div>
                <div class="form-group col-md-3">
                    <label for="transaction-cutomer">Customer</label>
                    <select
                        v-bind:class="['form-control', form.errors.has('customer') ? 'is-invalid' : '']"
                        id="transaction-cutomer" name="customer" v-model="form.customer">
                        <option v-for="customer in customers"
                            :key="customer.id" v-bind:value="customer.id">
                            {{ customer.name }}
                        </option>
                    </select>
                    <div class="invalid-feedback" v-if="form.errors.has('customer')"
                        v-text="form.errors.get('customer')"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="date-from">Date From</label>
                    <input type="date" class="form-control" id="date-from" name="date_from" v-model="form.date_from">
                </div>
                <div class="form-group col-md-3">
                    <label for="date-to">Date To</label>
                    <input type="date" class="form-control" id="date-to" name="date_to" v-model="form.date_to">
                </div>

        </div>
        <div class="form-row">
            <div class="form-group col-md-9">
                <label for="transaction-description">Description</label>
                <b-form-textarea v-model="form.description" id="transaction-description"
                    placeholder="Enter description" :rows="2" :max-rows="2">
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
      form: new Form({
        //   'date_from', 'date_to', 'amount', 'profit', 'hours',
        // 'status', 'description', 'customer_id'
        amount: { value: "", default: "" },
        date_from: { value: "", default: "" },
        date_to: { value: "", default: "" },
        customer: { value: "", default: "" },
      })
    };
  },
  methods: {
    onSubmit() {
      this.form
        .submit("post", "/api/payments")
        .then(transaction => this.$emit("payment_added", transaction))
        .catch(errors => console.log(errors));
    }
  },
  created() {
    axios.get("/api/customers").then(({ data }) => {
      this.customers = data;
      this.form.customer = this.customers.length > 0 ? this.customers[0].id : '';
    });
  }
};
</script>

