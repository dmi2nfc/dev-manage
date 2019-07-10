<template>
  <b-container>
    <!-- User Interface controls -->
    <b-row>
      <b-col class="my-1">
        <b-btn
          v-b-toggle.add_worker
          variant="success"
          @click="showAddNew = !showAddNew"
          :aria-expanded="showAddNew ? 'true' : 'false'">
          {{ showAddNew ? 'Close' : 'Add Worker' }}
        </b-btn>
        <b-collapse id="add_worker" v-model="showAddNew" class="mt-2">
          <b-card>
            <add-worker @worker_added="addWorker"></add-worker>
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
      <template slot="name" slot-scope="row">{{row.value}}</template>
      <!-- <template slot="isActive" slot-scope="row">{{row.value?'Yes :)':'No :('}}</template> -->
      <template slot="actions" slot-scope="row">
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
        <b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">
          Info
        </b-button>
        <b-button size="sm" @click.stop="row.toggleDetails">
          {{ row.detailsShowing ? 'Close' : 'Sprints' }}
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
          <active-sprints
            v-bind:sprints="row.item.active_sprints"
            @confirm_delete_sprint="event => confirmDeleteSprint(event)"
            @finish_sprint_confirmation="sprint => setFinishSprintMode(sprint)"
            ></active-sprints>

          <add-sprint v-bind:user="row.item.id"
            v-if="!row.item.active_sprints.includes(finishSprint.data)"
            @sprint_added="sprint => row.item.active_sprints.push(sprint)"></add-sprint>
          <finish-sprint
            v-else
            v-bind:sprint="finishSprint.data"
            @cancel_finish_sprint="() => {finishSprint = {id: false, data: false}}"
            @sprint_finished="sprint => sprintFinished(sprint)"></finish-sprint>
        </b-card>
      </template>
    </b-table>

    <b-row>
      <b-col md="6" class="my-1">
        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
      </b-col>
    </b-row>

    <!-- Info modal -->
    <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
      <pre>{{ modalInfo.content }}</pre>
    </b-modal>
    <b-modal id="deleteModal" @hide="resetModal" :title="modalInfo.title">
        <div class="d-block text-center">
        {{ modalInfo.content }}
        </div>
        <div slot="modal-footer" class="w-100">
            <b-btn @click="handleDeleteSprint"
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
Vue.use(BootstrapVue);

import AddSprint from '../components/AddSprint.vue';
import ActiveSprints from '../components/ActiveSprints.vue';
import FinishSprint from '../components/FinishSprint';
import AddWorker from "../components/AddWorker.vue";

export default {
  components: {
    'add-sprint': AddSprint,
    'active-sprints': ActiveSprints,
    'finish-sprint': FinishSprint,
    'add-worker': AddWorker
  },

  data () {
    return {
      showAddNew: false,
      items: [],
      fields: [
        { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
        // { key: 'age', label: 'Person age', sortable: true, 'class': 'text-center' },
        // { key: 'isActive', label: 'is Active' },
        { key: 'actions', label: '' }
      ],
      currentPage: 1,
      perPage: 5,
      totalRows: 1,
      pageOptions: [ 5, 10, 15, 50 ],
      sortBy: null,
      sortDesc: false,
      sortDirection: 'asc',
      filter: null,
      modalInfo: { title: '', content: '' },
      finishSprint: { id: false, data: false},

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
    info (item, index, button) {
      this.modalInfo.title = `Row index: ${index}`
      this.modalInfo.content = JSON.stringify(item, null, 2)
      this.$root.$emit('bv::show::modal', 'modalInfo', button)
    },
    resetModal () {
      this.modalInfo.title = ''
      this.modalInfo.content = ''
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    handleDeleteSprint() {
      // this.$root.$emit("bv::hide::modal", "deleteModal");
      // console.log(this.modalInfo.item);
      axios.delete(`api/sprints/${this.modalInfo.item.id}`).then(
        ({ data }) => {
          let itemKey = this.items.findIndex(e => e.id == data.user_id);
          this.items[itemKey].active_sprints = this.items[itemKey].active_sprints.filter(e => e.id !== data.id);
          this.hideDeleteModal();
        },
        err => comsole.log(err)
      );
    },
    hideDeleteModal() {
      this.$root.$emit("bv::hide::modal", "deleteModal");
    },
    confirmDeleteSprint({data, target}) {
      this.modalInfo.title = `Delete Sprint`;
      this.modalInfo.content = `Are you Sure Want to Delete Sprint?`;
      this.modalInfo.item = data;
      this.$root.$emit("bv::show::modal", "deleteModal", target);
    },
    setFinishSprintMode(sprint) {
      this.finishSprint = {id: sprint.id, data: sprint};
    },
    resetSprintFinish(){
      this.finishSprint = {id: false, data: false}
    },
    sprintFinished(sprint) {
      console.log(sprint);
      let itemKey = this.items.findIndex(e => e.id == sprint.user.id);
      this.items[itemKey].active_sprints = this.items[itemKey].active_sprints.filter(e => e.id !== sprint.id);
      this.resetSprintFinish();
    },
    addWorker(worker) {
      this.items.push(worker);
      this.showAddNew = false;
    }
  },
  created() {
      //make an ajax request to our server
      axios.get('/api/programmers')
          .then(({data}) => {
            this.items = data;
            this.totalRows = data.length
          });
  }
}
</script>

<!-- table-complete-1.vue -->