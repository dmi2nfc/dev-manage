<template>
    <div role="tablist">
        <h4 class="card-title">{{ sprints.length ? '' : 'No' }} Active Sprints</h4>
        <b-card no-body v-for="(sprint, index) in sprints" :key="index" class="mb-1">
            <b-card-header header-tag="header" class="p-1" role="tab">
                <div class="row">
                    <div class="col-sm-4">
                        {{ capitalizeFirstLetter(sprint.project.name) }} ({{capitalizeFirstLetter(sprint.project.owner.name)}})
                    </div>
                    <div class="col-sm-2">{{ capitalizeFirstLetter(sprint.rate_type) }} rate: {{ sprint.rate }} {{ sprint.currency.symbol }}</div>
                    <div class="col-sm-2">Total Worked: {{ minutesToTime(sprint.worked_time) }}</div>
                    <div class="col-sm-4">

                        <b-button variant="info"
                            @click.stop="$emit('finish_sprint_confirmation', sprint)">
                            Finish Sprint
                        </b-button>
                        <b-button
                            @click.stop="$emit('confirm_delete_sprint', {data: sprint, target: $event.target})"
                            class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i>
                        </b-button>
                    </div>

                </div>
            </b-card-header>
            <!-- <b-collapse v-bind:id="`sprint-${index}`" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <p class="card-text">
                        I start opened because
                        <code>visible</code> is
                        <code>true</code>
                    </p>
                    <p class="card-text">
                        {{ text }}
                    </p>
                </b-card-body>
            </b-collapse> -->
        </b-card>
    </div>
</template>

<script>
import moment from "moment";

export default {
  props: {
    sprints: Array,
  },
  data() {
    return {
    //   text: `
    //     Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
    //     richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor
    //     brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon
    //     tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
    //     assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore
    //     wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
    //     vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic
    //     synth nesciunt you probably haven't heard of them accusamus labore VHS.
    //   `
    };
  },

  methods: {
    minutesToTime(minutes) {
      return moment(minutes * 60 * 1000, "HHmmss").format("HH:mm");
    },

    capitalizeFirstLetter(str) {
      return str.charAt(0).toUpperCase() + str.substr(1);
    },
  }
};
</script>