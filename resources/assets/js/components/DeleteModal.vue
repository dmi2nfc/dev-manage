<template>
    <div>
        <b-button @click="showModal" ref="btnShow" class="btn btn-danger">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </b-button>
        <b-modal @hidden="onHidden">
            <div class="d-block">Are you sure want to delelte</div>
            <b-btn @click="hideModal">Cancel</b-btn>
            <b-btn @click="hideModal" class="btn btn-danger">Delete</b-btn>
        </b-modal>
    </div>
</template>

<script>
export default {
 props: {
    // item: ['item'],
    //   user: Object
    //project: Number
  },    
//   data() {
//     return {
//       name: "",
//       names: []
//     };
//   },
  methods: {
    methods: {
      showModal() {
        this.$root.$emit("bv::show::modal", "modal1");
      },
      hideModal() {
        this.$root.$emit("bv::hide::modal", "modal1");
      },
      onHidden(evt) {
        // Return focus to our Open Modal button
        // See accessibility below for additional return-focus methods
        this.$refs.btnShow.$el.focus();
      }
    }
  }
};
</script>