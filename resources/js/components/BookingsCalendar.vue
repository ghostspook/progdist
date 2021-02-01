<template>
    <div>
        <div style="height: 600px;">
            <vue-cal
                :events="events"
                :disable-views="['years', 'year']"
                locale="es"
                @ready ="fetchEvents"
                @view-change="fetchEvents"
            ></vue-cal>
        </div>
    </div>
</template>

<script>
import VueCal from 'vue-cal'
import 'vue-cal/dist/i18n/es.js'
import 'vue-cal/dist/vuecal.css'
import bookingsApi from '../services/booking'
import moment from 'moment'

export default {
    components: {
        VueCal
    },
    data() {
        return {
            bookings: []
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
                        id: b.id,
                        start: startTime.toDate(),
                        end: endTime.add(duration).toDate(),
                        title: b.program ? b.program.mnemonic : b.topic
                    }
            })
        }
    },
    methods: {
        async fetchEvents(eventData) {
            this.bookings = await bookingsApi.getByDateSpan(eventData.startDate.format('YYYY-MM-DD'), eventData.endDate.format('YYYY-MM-DD'))
        }
    }
}
</script>
