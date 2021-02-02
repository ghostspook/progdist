<template>
    <div>
        <h5 v-if="fetching">Cargando...</h5>
        <h5 v-if="booking" class="text-primary title">{{ booking.program ? booking.program.name : booking.topic }}</h5>
        <div v-if="booking" class="row">
            <div class="col-md-12">
                <ul class="no-bullets">
                    <li>
                        <font-awesome-icon icon="calendar"/>
                        {{ booking.booking_date | toLocalDateString }}
                    </li>
                    <li>
                        <font-awesome-icon icon="clock"/>
                        {{ booking.start_time | toLocalTimeString }} -
                        {{ booking.end_time | toLocalTimeString }}
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
