<template>
    <div class="card">
        <h5 class="card-header">
            Duplicar sesión
        </h5>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="row">
                <div class="my-calendar col-md-6" >
                    <vue-cal
                        hide-view-selector
                        :time="false"
                        active-view="month"
                        xsmall
                        @cell-click="onCellClick"
                        :events="events"
                    >
                    </vue-cal>
                    <div class="row justify-content-center mt-4">
                        <button  class="btn btn-success mt-2" @click="onCloneClick">¡Duplicar Ahora!</button>
                     </div>
                </div>
                    <div class="col-md-6 ">

                            <!-- <multiselect
                                v-model="events"
                                :options="events"
                                track-by="label"
                                label="label"
                                :multiple="true"
                                :taggable="true"
                                :showLabels="true"
                                :hide-selected="true"
                            ></multiselect> -->

                            <booking-cloning-list :cloningDates="events"
                                   @cloning-date-delete="onDeleteCloningDate"
                            >
                            </booking-cloning-list>


                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import VueCal from 'vue-cal'
//import Multiselect from "vue-multiselect";
import moment from 'moment'
import BookingCloningList from './BookingCloningList.vue';

export default {
    components: {
        VueCal,
        //Multiselect,
        BookingCloningList,
    },
    props: {
        booking: {
            type: Object,
            required: true
        },
    },
    data () {
        return {
            events: [],
            cloningDate: null,
        }
    },
    computed: {

        // selectedDates() {
        //     var selectedDates = []
        //     this.events.array.forEach(element => {

        //     });

        //     return this.events.map((event) => moment(event.start).format("DD/MM/YYYY"));
        // },
    },
    methods: {
        onCellClick (e) {

            var eventsThisDay = this.events.filter( (ev) =>
                        moment(e).startOf('day').isSame(ev.start, "day")
                    );

            if (eventsThisDay.length == 0) {
                this.events.push({
                    start: moment(e).startOf('day').toDate(),
                    end: moment(e).endOf('day').toDate(),
                    title: this.booking.program.mnemonic,
                    label: moment(e).format("DD/MM/YYYY"),
                })
                this.cloningDate = moment(e).startOf('day').toDate()

            } else {
                this.events = this.events.filter(evt => evt !== eventsThisDay[0])

            }

        },

        onDeleteCloningDate(dateToDelete){
            this.events = this.events.filter(
                      (e) => e.start != dateToDelete.cloningDate
                  )

        },

        onCloneClick(){

        }

    }
}
</script>

<style scoped>
.my-calendar {
    height: 300px;
    width: 300px;
}



</style>
