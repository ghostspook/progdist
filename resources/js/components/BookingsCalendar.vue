<template>
    <div id="calendar_are">
        <div class="row">
            <div class="col-md-12">
                <div class="calendar-container">
                    <vue-cal
                        :events="events"
                        :disable-views="['years', 'year']"
                        locale="es"
                        :editable-events="{ title: false, drag: false, resize: false, delete: false, create: true }"
                        :drag-to-create-threshold="drag_threshold"
                        @ready ="fetchEvents"
                        @view-change="fetchEvents"
                        @event-focus="onEventFocus"
                        @event-drag-create="onEventDragCreate"
                        ref="calendar"
                        :active-view="active_view"
                    ></vue-cal>
                </div>
            </div>
        </div>
        <transition name="slide">
        <div class="slidein" v-if="displayEventDetails">
            <booking-editor
                :booking-id = "selectedBookingId"
                :programs = "programs"
                :areas= "areas"
                :instructors= "instructors"
                :physicalrooms= "physicalrooms"
                :virtualrooms= "virtualrooms"
                :selectableSupportPeople= "selectableSupportPeople"
                v-if="canCreateAndEditBookings"
                @booking-delete="onBookingDelete"
                @booking-save="onBookingSave"
            />
            <booking-info
                v-if="!canCreateAndEditBookings"
                :booking-id="selectedBookingId"
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
import userApi from '../services/user'
import programsApi from "../services/program";
import areasApi from "../services/area";
import instructorsApi from "../services/instructorarea";
import physicalroomsApi from "../services/physicalroom";
import virtualRoomsApi from "../services/virtualroom";
import supportPeopleApi from "../services/supportperson";
import moment from 'moment'
import BookingInfo from './BookingInfo.vue'
import BookingEditor from './BookingEditor.vue'

const ROLE_COORD = 1;
const ROLE_ACAD = 2;
const ROLE_TI = 3;

const SUPPORT_TYPE_PHYSICAL = 0;
const SUPPORT_TYPE_VIRTUAL = 1;

const DEFAULT_MEETING_ID = 38; //38 : id for REUNION

export default {
    components: {
        VueCal,
        BookingInfo,
        BookingEditor
    },
    data() {
        return {
            bookings: [],
            virtualrooms: [],
            supportpeople: [],
            selectedBookingId: 0,
            displayEventDetails: false,
            user: null,
            drag_threshold: 20,
            creating: false,
            active_view: "week",
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
                        class: b.program && b.program.class ? b.program.class : ""
                    }
            })
        },
        canCreateAndEditBookings() {
            return (!this.user) ? false : this.user.authorized_account.can_create_and_edit_bookings == 1
        },

        selectableSupportPeople() {
            var returnList = [];
            this.supportpeople.forEach((person) => {
                returnList.push({
                    support_person_id: person.id,
                    name: person.name,
                    mnemonic: person.mnemonic,
                    role: ROLE_COORD,
                    type: SUPPORT_TYPE_PHYSICAL,
                    label: "Coord - " + person.mnemonic + " - Físico",
                });
                returnList.push({
                    support_person_id: person.id,
                    name: person.name,
                    mnemonic: person.mnemonic,
                    role: ROLE_COORD,
                    type: SUPPORT_TYPE_VIRTUAL,
                    label: "Coord - " + person.mnemonic + " - Virtual",
                });
                returnList.push({
                    support_person_id: person.id,
                    name: person.name,
                    mnemonic: person.mnemonic,
                    role: ROLE_ACAD,
                    type: SUPPORT_TYPE_PHYSICAL,
                    label: "Acad - " + person.mnemonic + " - Físico",
                });
                returnList.push({
                   support_person_id: person.id,
                    name: person.name,
                    mnemonic: person.mnemonic,
                    role: ROLE_ACAD,
                    type: SUPPORT_TYPE_VIRTUAL,
                    label: "Acad - " + person.mnemonic + " - Virtual",
                });
                returnList.push({
                    support_person_id: person.id,
                    name: person.name,
                    mnemonic: person.mnemonic,
                    role: ROLE_TI,
                    type: SUPPORT_TYPE_PHYSICAL,
                    label: "TI - " + person.mnemonic + " - Físico",
                });
                returnList.push({
                    support_person_id: person.id,
                    name: person.name,
                    mnemonic: person.mnemonic,
                    role: ROLE_TI,
                    type: SUPPORT_TYPE_VIRTUAL,
                    label: "TI - " + person.mnemonic + " - Virtual",
                });
            });
            return returnList;
        },
    },
    async mounted() {
        await this.fetchPrograms()
        await this.fetchAreas()
        await this.fetchInstructors()
        await this.fetchPhysicalRooms()
        await this.fetchVirtualRooms()
        await this.fetchSupportPeople()



    },
    methods: {
        async fetchPrograms() {
            try {
                this.programs = await programsApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de programas"
                });
            }
        },
        async fetchAreas() {
            try {
                this.areas = await areasApi.getAll()
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de áreas"
                });
            }
        },

        async fetchInstructors() {
            try {
                this.instructors = await instructorsApi.getAll()
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de Profesores"
                });
            }
        },

        async fetchPhysicalRooms() {
            try {
                this.physicalrooms = await physicalroomsApi.getAll()
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de Aulas Físicas"
                });
            }
        },

        async fetchVirtualRooms() {
            try {
                this.virtualrooms = await virtualRoomsApi.getAll()
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de Aulas Virtuales"
                });
            }
        },

        async fetchSupportPeople() {
            try {
                this.supportpeople = await supportPeopleApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de personas de soporte"
                });
            }
        },



        async fetchEvents(eventData) {
            if (!this.user) {
                this.user = await userApi.getMyUser()
            }

            this.bookings = await bookingsApi.getByDateSpan(eventData.startDate.format('YYYY-MM-DD'), eventData.endDate.format('YYYY-MM-DD'))
        },
        onEventFocus(eventData) {
            if (!eventData.bookingId) {
                return;
            }
            this.selectedBookingId = eventData.bookingId
            this.displayEventDetails = true
        },
        async onEventDragCreate(e) {
            e.class = 'vuecal__event blue'

            this.creating = true;
            try {
                var bookingObj = {
                    booking_date: moment(e.start).startOf('day'),
                    program: DEFAULT_MEETING_ID,
                    topic: '',
                    startTime: moment(e.start).toDate(),
                    endTime: moment(e.end).toDate(),
                    area: null,
                    instructor: null,
                    physicalRoom: null,
                    virtualRoom: null,
                    supportPeople: [],
                    link: null
                };
                var responseData = await bookingsApi.create({
                    newBooking: bookingObj,
                });

                this.onEventFocus({bookingId: responseData.bookingId})
            } catch (e) {
                console.log(e.response.data);
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error",
                    text: e.response.data.errorMessage,
                });
            } finally {
                this.creating = false;
                await this.refreshCalendar(e)
            }
        },
        async refreshCalendar(e) {
            var startDate = null
            var endDate = null
            if (this.active_view == "week") {
                startDate = moment(e.start).startOf('isoWeek')
                endDate = moment(e.start).endOf('isoWeek')
            } else if (this.active_view == "month") {
                startDate = moment(e.start).startOf('month')
                endDate = moment(e.start).endOf('month')
            }
            await this.fetchEvents({startDate: startDate, endDate: endDate})
        },
        async onBookingDelete (e) {
            this.toggle()
            this.selectedBookingId = 0
            await this.refreshCalendar(e)
        },
        async onBookingSave (e) {
            this.toggle()
            this.selectedBookingId = 0
            await this.refreshCalendar(e)
        },
        toggle() {
            this.displayEventDetails = !this.displayEventDetails;
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

<style>
.vuecal__event.orange {background-color: rgba(253, 156, 66, 0.9);border: 1px solid rgb(233, 136, 46);color: #fff;}
.vuecal__event.green {background-color: rgba(164, 230, 210, 0.9);border: 1px solid rgb(144, 210, 190);}
.vuecal__event.red {background-color: rgba(255, 102, 102, 0.9);border: 1px solid rgb(235, 82, 82);color: #fff;}
.vuecal__event.blue {background-color: rgba(102, 181, 255, 0.9);border: 1px solid rgb(102, 181, 255);color: #fff;}
.vuecal__event {background-color: rgba(182, 191, 201, 0.9);border: 1px solid rgb(182, 191, 201);color: #fff;}
</style>
