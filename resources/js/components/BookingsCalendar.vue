<template>
    <div id="calendar_are">
        <div class="row">
            <div class="col-md-12">
                <div class="calendar-container">
                    <vue-cal
                        :events="events"
                        :disable-views="['years', 'year']"
                        locale="es"
                        @ready ="fetchEvents"
                        @view-change="fetchEvents"
                        @event-focus="onEventFocus"
                    ></vue-cal>
                </div>
            </div>
        </div>
        <transition name="slide">
        <div class="slidein" v-if="displayEditForm">
            <booking-info
                :bookingId="selectedBookingId"
            ></booking-info>
            <button class="close-btn" @click="toggle">X</button>
        </div>
        </transition>
    </div>
</template>

<script>
import VueCal from 'vue-cal'
import 'vue-cal/dist/i18n/es.js'
import 'vue-cal/dist/vuecal.css'
import bookingsApi from '../services/booking'
import moment from 'moment'
import BookingInfo from './BookingInfo.vue'

export default {
    components: {
        VueCal,
        BookingInfo
    },
    data() {
        return {
            bookings: [],
            selectedBookingId: 0,
            displayEditForm: false
        }
    },
    computed: {
        events() {
            return this.bookings.map(b => {
                var bookingDate = moment(b.booking_date)
                var startTime = moment(b.start_time)
                var endTime = moment(b.end_time)
                var duration = moment.duration(endTime.diff(startTime));

                startTime = bookingDate.add(startTime.hours(), 'hours').add(startTime.minutes(), 'minutes')
                endTime = startTime.clone()
                return {
                        bookingId: b.id,
                        start: startTime.toDate(),
                        end: endTime.add(duration).toDate(),
                        title: b.program ? b.program.mnemonic : b.topic,
                    }
            })
        }
    },
    methods: {
        async fetchEvents(eventData) {
            this.bookings = await bookingsApi.getByDateSpan(eventData.startDate.format('YYYY-MM-DD'), eventData.endDate.format('YYYY-MM-DD'))
        },
        onEventFocus(eventData) {
            this.selectedBookingId = eventData.bookingId
            this.displayEditForm = true
        },
        toggle() {
            this.displayEditForm = !this.displayEditForm;
        }
    }
}
</script>

<style scoped>
.calendar-container {
  height: 100%;
}

.slidein {
  width: 400px;
  padding: 2em 3em;
  position: fixed;
  z-index: 100;
  top: 0;
  right: 0;
  background: #ffffff;
  height: 100%;
  box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.5);
  transition: all 0.5s ease-in-out;
}

/* before the element is shown, start off the screen to the right */
.slide-enter, .slide-leave-active {
  right: -100%;
}

.close-btn {
  border: none;
  font-weight: bold;
  font-size: 1em;
  background: transparent;
  position: absolute;
  top: 0;
  left: 0;
  padding: 0.5em;
}

/* General styles unrelated to slide in */
body {
  font-family: 'Mulish', sans-serif;
}

.toggle {
  margin: 1em;
}

button {
  padding: .5em 1em;
  border-radius: 3em;
  font-size: 1.1em;
}

h1 {
  font-weight: 200;
}
</style>
