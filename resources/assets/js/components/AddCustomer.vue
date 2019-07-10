<template>
    <div class="sprint">
        <h4 class="card-title">Create New Customer</h4>
        <form method="POST" action="/api/customers" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="customer-name">Name</label>
                    <input type="text" v-bind:class="['form-control', form.errors.has('name') ? 'is-invalid' : '']" id="customer-name" name="name" v-model="form.name">
                    <div class="invalid-feedback" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="customer-email">Email</label>
                    <input type="email" v-bind:class="['form-control', form.errors.has('email') ? 'is-invalid' : '']" id="customer-email" name="email" v-model="form.email">
                    <div class="invalid-feedback" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <b-form-textarea v-model="form.notes" placeholder="Enter Notes" :rows="4" :max-rows="6">
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
    user: Number
    //   user: Object
    //project: Number
  },
  data() {
    return {
      form: new Form({
        name: { value: "", default: "" },
        email: { value: "", default: "" },
        notes: { value: "", default: "" }
      })
    };
  },
  methods: {
    onSubmit() {
      this.form
        .post("/api/customers")
        .then(customer => this.$emit('customer_added', customer))
        .catch(errors => console.log(errors));
    }
  }
};
</script>