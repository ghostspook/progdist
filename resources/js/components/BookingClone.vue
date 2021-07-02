<template>
    <div class="card">
        <h5 class="card-header">
            Clonar Sesión
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
                        <button  :disabled="clonning" class="btn btn-warning mt-2" @click="onCloneClick">Clonar Ahora!</button>
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
        <modal name="cloneConfirmation" height="auto">
            <div class="card">
                <div class="card-header">
                    Clonación de Sesión
                </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <p>
                                <strong>¿Está seguro que desea clonar esta sesión en las fechas seleccionadas?</strong>
                                Esta operación copiará tema, horario, área, profesor, aula física, aula virtual, link y equipo de soporte
                                a todas las fechas seleccionadas.
                            </p>
                            <div>
                                <button class="btn btn-default pull-right" @click="doNotClone">Cancelar</button>
                                <button class="btn btn-warning pull-right" @click="doClone">Sí</button>
                            </div>
                        </div>
                    </div>
            </div>
        </modal>
    </div>
</template>

<script>
import VueCal from 'vue-cal'
import VModal  from "vue-js-modal";

//import Multiselect from "vue-multiselect";
import moment from 'moment'
import BookingCloningList from './BookingCloningList.vue';

import bookingsApi from '../services/booking'

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
            clonning: false,
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

        async onCloneClick(){
            this.$modal.show('cloneConfirmation')

        },

       async doClone (){
            console.log("Capacity", this.booking.virtualRoomCapacity)
            this.clonning = true
            self = this

            try {
                var bookingObj = {
                   // booking_date: moment(this.booking.booking_date).toDate(),
                    program: this.booking.program ? this.booking.program.id : null,
                    topic: this.booking.topic,
                    startTime: this.booking.start_time,
                    endTime: this.booking.end_time,
                    area: this.booking.area ?  this.booking.area.id : null,
                    instructor: this.booking.instructor ? this.booking.instructor.id : null,
                    physicalRoom: this.booking.physical_room ? this.booking.physical_room.id : null,
                    virtualRoom: this.booking.virtual_meeting ? this.booking.virtual_meeting.virtual_room_id : null,
                    supportPeople: this.booking.support_people,
                    link: this.booking.virtual_meeting ? this.booking.virtual_meeting.link_id : null,
                    virtualRoomCapacity: this.booking.virtual_room_capacity,
                  };


                var responseData;
                var clonningDate

                for (var i = 0; i < self.events.length ; i++) {
                    clonningDate = self.events[i]

                    bookingObj.booking_date =  moment(clonningDate.start).toDate()
                    responseData  = await bookingsApi.create(
                        {
                            newBooking: bookingObj,
                        }
                    );
                }

                this.$emit('booking-clonning-success')



            } catch (e) {
                console.log(e)
                console.log(e.response.data);
                this.$emit('booking-clonning-error', e)

            } finally {
                this.clonning = false;
                this.$modal.hide('cloneConfirmation')

            }

        },

        doNotClone(){
             this.$modal.hide('cloneConfirmation')
        },


    }
}
</script>

<style scoped>
.my-calendar {
    height: 300px;
    width: 300px;
}



</style>
