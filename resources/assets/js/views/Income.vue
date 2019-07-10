<template>
  <b-container>
    <b-row>
      <b-col class="my-1">
        <b-btn
          v-b-toggle.add_payment
          variant="success"
          @click="showAddNew = !showAddNew"
          :aria-expanded="showAddNew ? 'true' : 'false'">
          {{ showAddNew ? 'Close' : 'Add New Payment' }}
        </b-btn>
        <b-collapse id="add_payment" v-model="showAddNew" class="mt-2">
          <b-card>
            <add-payment @payment_added="addPayment"></add-payment>
          </b-card>
        </b-collapse>
      </b-col>
    </b-row>
    <b-row>
      <b-col md="4" class="my-1">
        <b-form-group horizontal label="Filter" class="mb-0">
          <b-input-group>
            <b-form-input v-model="filter" placeholder="Type to Search" />
            <b-input-group-append>
              <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col md="4" class="my-1">
        <b-form-group horizontal label="Per page" class="mb-0">
          <b-form-select :options="pageOptions" v-model="perPage" />
        </b-form-group>
      </b-col>
    </b-row>

    <!-- Main table element -->
    <b-table show-empty
             stacked="md"
             :items="items"
             :fields="fields"
             :current-page="currentPage"
             :per-page="perPage"
             :filter="filter"
             :sort-by.sync="sortBy"
             :sort-desc.sync="sortDesc"
             :sort-direction="sortDirection"
             @filtered="onFiltered"
    >
      <template slot="amount" slot-scope="row">{{row.value}}</template>
      <template slot="date" slot-scope="row">{{row.value}}</template>
      <template slot="customer" slot-scope="row">{{row.value}}</template>
      <!-- <template slot="isActive" slot-scope="row">{{row.value?'Yes :)':'No :('}}</template> -->
      <template slot="actions" slot-scope="row">
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
        <!-- <b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">
          Info
        </b-button>
        <b-button size="sm" @click.stop="row.toggleDetails">
          {{ row.detailsShowing ? 'Close' : 'Sprints' }}
        </b-button> -->
        <b-button size="sm" @click.stop="confirmDelete(row.item, row.index, $event.target)"
          class="btn btn-danger">
          Delete
        </b-button>
      </template>
      <template slot="row-details" slot-scope="row">
        <b-card>
          <!-- <ul>
            <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value}}</li>
          </ul> -->
          <!-- <ul>
            <li v-for="(value, key) in row.item.sprints" :key="key">{{ key }}: {{ value }}</li>
          </ul>   -->
          <!-- <active-sprints v-bind:sprints="row.item.sprints"></active-sprints> -->

          <!-- <add-project v-bind:user="row.item.id"
            @sprint_added="sprint => row.item.sprints.push(sprint)"></add-project> -->
        </b-card>
      </template>
    </b-table>

    <b-row>
      <b-col md="6" class="my-1">
        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
      </b-col>
    </b-row>

    <!-- Info modal -->
    <!-- <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
      <pre>{{ modalInfo.content }}</pre>
    </b-modal> -->
    <b-modal id="deleteModal" @hide="resetModal" :title="modalInfo.title">
      <div class="d-block text-center">
        {{ modalInfo.content }}
      </div>
      <div slot="modal-footer" class="w-100">
         <b-btn @click="handleDelete"
          size="sm" class="float-right
          btn btn-danger mx-1">Delete</b-btn>
         <b-btn size="sm"
          class="float-right"
          variant="primary"
          @click="hideDeleteModal">
           Close
         </b-btn>
       </div>
    </b-modal>

  </b-container>
</template>

<script>
// import '../bootstrap';
Vue.use(BootstrapVue);

import Preloader from "../components/Preloader.vue";
import AddPayment from '../components/AddPayment.vue';

export default {
    // props: {
    //     programmers: Array
    // },
  components: {
    'add-payment': AddPayment,
    "preloader": Preloader
  },

  data () {
    return {
      showAddNew: false,
      items: [],
      showLoader: false,
      fields: [
        { key: 'amount', label: 'Amount', sortable: true },
        { key: 'created_at', label: 'Date', sortable: true },
        { key: 'customer.name', label: 'Customer', sortable: true },
        // { key: 'age', label: 'Person age', sortable: true, 'class': 'text-center' },
        // { key: 'isActive', label: 'is Active' },
        { key: 'actions', label: '' }
      ],
      currentPage: 1,
      perPage: 5,
      totalRows: 1,
      pageOptions: [ 5, 10, 15, 50 ],
      sortBy: 'created_at',
      sortDesc: true,
      sortDirection: 'asc',
      filter: null,
      modalInfo: { title: '', content: '' },


    }
  },
  computed: {
    sortOptions () {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => { return { text: f.label, value: f.key } })
    }
  },
  methods: {
    // info (item, index, button) {
    //   this.modalInfo.title = `Row index: ${index}`
    //   this.modalInfo.content = JSON.stringify(item, null, 2)
    //   this.$root.$emit('bv::show::modal', 'modalInfo', button)
    // },
    resetModal () {
      this.modalInfo.title = ''
      this.modalInfo.content = ''
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    addPayment(payment) {
        this.items.push(payment);
        this.showAddNew = false;
    },
    confirmDelete(item, index, button) {
      this.modalInfo.title = `Delete Project`;
      this.modalInfo.content = `Are you Sure Want to Delete ${item.name}?`;
      this.modalInfo.item = item;
      this.$root.$emit("bv::show::modal", "deleteModal", button);
    },
    handleDelete() {
      // this.$root.$emit("bv::hide::modal", "deleteModal");
      // console.log(this.modalInfo.item);
      axios.delete(`api/projects/${this.modalInfo.item.id}`)
        .then(
          ({data}) => {
            this.items = this.items.filter(e => e.id !== data.id);
            this.hideDeleteModal();
          },
          err => comsole.log(err)
        );
    },
    hideDeleteModal(){
      this.$root.$emit("bv::hide::modal", "deleteModal");
    },
  },
  created() {
      //make an ajax request to our server
      this.showLoader = true;
      axios.get('/api/payments')
          .then(({data}) => {
            this.items = data;
            this.totalRows = data.length
          })
          .then(()=>(this.showLoader = false));
  }
}
</script>

<!-- table-complete-1.vue -->