<template>
    <div class="sprint">
        <h4 class="card-title">Create New Worker</h4>
        <form method="POST" action="/api/workers" @submit.prevent="onSubmit" @keydown="form.errors.clear($event.target.name)">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="worker-name">Name</label>
                    <input type="text" v-bind:class="['form-control', form.errors.has('name') ? 'is-invalid' : '']" id="worker-name" name="name" v-model="form.name">
                    <div class="invalid-feedback" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="worker-email">Email</label>
                    <input type="email" v-bind:class="['form-control', form.errors.has('email') ? 'is-invalid' : '']" id="worker-email" name="email" v-model="form.email">
                    <div class="invalid-feedback" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></div>
                </div>
                <div class="form-group col-md-3">
                    <label for="worker-name">Password</label>
                    <input type="text" v-bind:class="['form-control', form.errors.has('password') ? 'is-invalid' : '']" id="worker-password" name="password" v-model="form.password">
                    <div class="invalid-feedback" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></div>
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
        password: { value: "worker", default: "worker" }
      })
    };
  },
  methods: {
    onSubmit() {
      this.form
        .post("/api/workers")
        .then(worker => this.$emit('worker_added', worker))
        .catch(errors => console.log(errors));
    }
  }
};
</script>