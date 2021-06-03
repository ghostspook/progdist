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
                        <button  :disabled="clonning" class="btn btn-success mt-2" @click="onCloneClick">¡Duplicar Ahora!</button>
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
            console.log("Booking To Clone", this.booking)

            if (  this.booking.start_time >= this.booking.end_time){
                //this.$modal.show('checkTime')
                return
            }

            this.clonning = true


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
                  };


                var responseData;

                this.events.forEach( async (clonningDate) =>  {
                    console.log("Clonning", bookingObj)
                    bookingObj.booking_date =  moment(clonningDate.start).toDate()
                    console.log("Cloned Booking Date", bookingObj.booking_date)

                    responseData  = await bookingsApi.create(
                        {
                            newBooking: bookingObj,
                        }
                    );
                });




            } catch (e) {
                console.log(e)
                console.log(e.response.data);
                // this.$notify({
                //     group: "notificationGroup",
                //     type: "error",
                //     title: "Error",
                //     text: e.response.data.errorMessage,
                // });
            } finally {
                this.clonning = false;

                // this.$notify({
                //     group: "notificationGroup",
                //     type: "success",
                //     title: "Registro guardado exitosamente.",
                // });
            }

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
