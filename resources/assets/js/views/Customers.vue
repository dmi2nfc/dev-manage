<template>
  <b-container>
    <!-- User Interface controls -->
    <b-row>
      <b-col class="my-1">
        <b-btn
          v-b-toggle.add_customer
          variant="success"
          @click="showAddNew = !showAddNew"
          :aria-expanded="showAddNew ? 'true' : 'false'">
          {{ showAddNew ? 'Close' : 'Add New' }}
        </b-btn>
        <b-collapse id="add_customer" v-model="showAddNew" class="mt-2">
          <b-card>
            <add-customer @customer_added="addCustomer"></add-customer>
          </b-card>
        </b-collapse>
      </b-col>
    </b-row>
    <preloader v-if="this.showLoader"></preloader>
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
      <!-- <b-col md="4" class="my-1">
        <b-form-group horizontal label="Per page" class="mb-0">
          <b-form-select :options="pageOptions" v-model="perPage" />
        </b-form-group>
      </b-col> -->
    </b-row>

    <!-- Main table element -->
    <b-table show-empty stacked="md"
      :items="items"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      @filtered="onFiltered">
      <template slot="name" slot-scope="row">{{row.value}}</template>
      <!-- <template slot="isActive" slot-scope="row">{{row.value?'Yes :)':'No :('}}</template> -->
      <template slot="actions" slot-scope="row">
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
        <!-- <b-btn v-b-modal.modalsm variant="primary">Small modal</b-btn> -->
      </template>
      <template slot="row-details" slot-scope="row">
        <b-card>
          <!-- <ul>
            <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value}}</li>
          </ul> -->
          <!-- <ul>
            <li v-for="(value, key) in row.item.sprints" :key="key">{{ key }}: {{ value }}</li>
          </ul>   -->
          <!-- <active-sprints v-bind:sprints="row.item.sprints"></active-sprints>

          <add-sprint v-bind:user="row.item.id"></add-sprint> -->
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

    <!-- <b-modal @hidden="onHidden">
      <div class="d-block">Are you sure want to delelte</div>
      <b-btn @click="hideModal">Cancel</b-btn>
      <b-btn @click="hideModal" class="btn btn-danger">Delete</b-btn>
    </b-modal> -->

  </b-container>
</template>

<script>
// import '../bootstrap';
Vue.use(BootstrapVue);

import AddCustomer from "../components/AddCustomer.vue";
import ActiveSprints from "../components/ActiveSprints.vue";
import Preloader from "../components/Preloader.vue";
// import DeleteModal from '../components/DeleteModal.vue';

export default {
  // props: {
  //     programmers: Array
  // },
  components: {
    "add-customer": AddCustomer,
    "active-sprints": ActiveSprints,
    "preloader": Preloader
    // 'delete-btn': DeleteModal
  },

  data() {
    return {
      showAddNew: false,
      items: [],
      showLoader: false,
      fields: [
        { key: "name", label: "Name", sortable: true, sortDirection: "desc" },
        // { key: 'age', label: 'Person age', sortable: true, 'class': 'text-center' },
        // { key: 'isActive', label: 'is Active' },
        { key: "actions", label: "" }
      ],
      currentPage: 1,
      perPage: 5,
      totalRows: 1,
      pageOptions: [5, 10, 15, 50],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      modalInfo: { title: "", content: "", item: {} },
    };
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields.filter(f => f.sortable).map(f => {
        return { text: f.label, value: f.key };
      });
    }
  },
  methods: {
    info(item, index, button) {
      this.modalInfo.title = `Row index: ${index}`;
      this.modalInfo.content = JSON.stringify(item, null, 2);
      this.$root.$emit("bv::show::modal", "modalInfo", button);
    },
    confirmDelete(item, index, button) {
      this.modalInfo.title = `Delete Customer`;
      this.modalInfo.content = `Are you Sure Want to Delete ${item.name}?`;
      this.modalInfo.item = item;
      this.$root.$emit("bv::show::modal", "deleteModal", button);
    },
    resetModal() {
      this.modalInfo.title = "";
      this.modalInfo.content = "";
      this.modalInfo.item = {};
    },
    handleDelete() {
      // this.$root.$emit("bv::hide::modal", "deleteModal");
      // console.log(this.modalInfo.item);
      axios.delete(`api/customers/${this.modalInfo.item.id}`)
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
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    addCustomer(customer) {
      this.items.push(customer);
      this.showAddNew = false;
    }
  },
  created() {
    //make an ajax request to our server
    this.showLoader = true;
    axios.get("/api/customers")
    .then(({ data }) => {
      this.items = data;
      this.totalRows = data.length;
    })
    .then(()=>(this.showLoader = false));
  }
};
</script>

<!-- table-complete-1.vue -->