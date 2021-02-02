<template>
    <div>
        <h4 v-if="fetching">Cargando...</h4>
        <h4 v-if="booking" class="text-primary title">{{ booking.program ? booking.program.name : booking.topic }}</h4>
        <div v-if="booking" class="row">
            <div class="col-md-12">
                <ul class="no-bullets">
                    <li>
                        <font-awesome-icon icon="calendar-day"/>
                        {{ booking.booking_date | toLocalDateString }}
                    </li>
                    <li>
                        <font-awesome-icon icon="clock"/>
                        {{ booking.start_time | toLocalTimeString }} -
                        {{ booking.end_time | toLocalTimeString }}
                    </li>
                    <li v-if="booking.instructor">
                        <font-awesome-icon icon="chalkboard-teacher"/>
                        {{ booking.instructor.name }}
                    </li>
                    <li v-if="booking.area">
                        <font-awesome-icon icon="book"/>
                        {{ booking.area.name }}
                    </li>
                    <li v-if="booking.physical_room">
                        <font-awesome-icon icon="chalkboard"/>
                        {{ booking.physical_room.name }}
                    </li>
                    <li v-if="booking.virtual_meeting_link">
                        <font-awesome-icon icon="link"/>
                        {{ booking.virtual_meeting_link.virtual_room ? booking.virtual_meeting_link.virtual_room.name : "" }}
                        <br>
                        <a :href="booking.virtual_meeting_link.link" target="_blank">
                            {{ booking.virtual_meeting_link.link }}
                        </a>
                        <br>
                        PASS: {{ booking.virtual_meeting_link.password }}
                        <br>
                        <span v-if="booking.virtual_meeting_link.waiting_room">
                            <font-awesome-icon icon="hourglass-start"/> Sala de espera
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import bookingsApi from '../services/booking'
import moment from 'moment'

export default {
    components: {
    },
    props: {
        bookingId: {
            type: Number,
            required: true
        }
    },
    data(){
        return {
            booking:  null,
            fetching: true
        }
    },
    filters: {
        toLocalDateString(value) {
            return moment(value).toDate().toLocaleDateString()
        },
        toLocalTimeString(value) {
            return moment(value).toDate().toLocaleTimeString()
        }
    },
    async mounted() {
        await this.fetchBooking()
    },
    watch: {
        bookingId:
            async function(val) {
                await this.fetchBooking()
            }
    },
    methods: {
        async fetchBooking() {
            this.fetching = true
            this.booking = null
            try {
                this.booking = await bookingsApi.get(this.bookingId)
            } catch (e) {
                console.log(e)
            } finally {
                this.fetching = false
            }
        }
    }
}
</script>

<style scoped>
ul.no-bullets {
  list-style-type: none; /* Remove bullets */
  padding: 0; /* Remove padding */
  margin: 0; /* Remove margins */
}
</style>
