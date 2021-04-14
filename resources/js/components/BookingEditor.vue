<template>
    <div>
        <h4 v-if="fetching">Cargando...</h4>
        <div v-if="booking">
            <h4 v-if="!editProgram" class="text-primary title" @click="onProgramNameClick">
                {{ (!booking.program) ? '-' : booking.program.name }}
            </h4>
            <v-select
                v-show="editProgram"
                id="programs"
                :options="sortedPrograms"
                @input="onProgramChange"
                label="mnemonic"
                v-model="booking.program"
                :reduce="(sp) => (!sp ? null : sp.id)"
            />
            <div v-if="!editDate" @click="onDateClick">
                <font-awesome-icon icon="calendar-day"/>
                {{ booking.booking_date | toLocalDateString }}
            </div>


            <datepicker
                v-if="editDate"
                v-model="booking.booking_date"
                :language="es"
                :bootstrap-styling="true"
                @closed="onDateCalendarClosed"
            />

            <div v-if="!editTime" @click="onTimeClick">
                <font-awesome-icon icon="clock"/>
                {{ booking.start_time }} -
                {{ booking.end_time  }}
            </div>


            <div  v-if="editTime">
                <label for="startTime">Inicia: </label>
                <input v-model="booking.start_time" id="startTime" type="time" min="06:00" max="23:55" required>

                <label for="endTime">Termina: </label>
                <input v-model="booking.end_time" id="endTime" type="time" min="06:00" max="23:55" required>

            </div>

            <div v-if="!editArea" @click="onAreaClick">
                <font-awesome-icon icon="book"/>
                {{ (!booking.area) ? '-' : booking.area.name }}
            </div>

            <v-select
                v-if="editArea"
                id="areas"
                :options="sortedAreas"
                @input="onAreaChange"
                label="mnemonic"
                v-model="booking.area"
                :reduce="(sa) => (!sa ? null : sa.id)"
            />

            <div v-if="!editInstructor" @click="onInstructorClick">
                <font-awesome-icon icon="chalkboard-teacher"/>
                {{ (!booking.instructor) ? '-' : booking.instructor.name }}
            </div>


            <v-select
                v-if="editInstructor"
                :options="selectableInstructors"
                @input="onInstructorChange"
                label="mnemonic"
                v-model="booking.instructor"
                :reduce="(si) => (!si ? null : si.id)"
            />


            <div v-if="!editPhysicalRoom" @click="onPhysicalRoomClick">
                <font-awesome-icon icon="chalkboard"/>
                {{ (!booking.physical_room) ? '-' : booking.physical_room.name }}
            </div>

            <v-select
                v-if="editPhysicalRoom"
                id="physicalroom"
                :options="sortedPhysicalRooms"
                @input="onPhysicalRoomChange"
                label="mnemonic"
                v-model="booking.physical_room"
                :reduce="(r) => (!r ? null : r.id)"
            />

            <div v-if="!editLink">
                <div @click="onVirtualRoomClick">
                    <font-awesome-icon icon="link"/>
                    <!-- {{ booking.virtual_meeting_link.virtual_room ? booking.virtual_meeting_link.virtual_room.name : "" }} -->
                    {{ booking.virtual_meeting_link ? booking.virtual_meeting_link.virtual_room_name : "-" }}

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
                </div>
            </div>

            <v-select
                v-if="editLink"
                id="virtualmeetinglink"
                :options="selectableLinks"
                @input="onLinkChange"
                label="link"
                v-model="booking.virtual_meeting_link"
                :reduce="(v) => (!v ? null : v.id)"
            />


        </div>
    </div>
</template>

<script>
import bookingsApi from '../services/booking'
import virtualMeetingLinksApi from '../services/programvirtualmeetinglink'
import moment from 'moment'
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import Datepicker from "vuejs-datepicker";
import { en, es } from "vuejs-datepicker/dist/locale";


export default {
    components: {
        vSelect,
        Datepicker,

    },
    props: {
        bookingId: {
            type: Number,
            required: true
        },
        programs: {
            type: Array,
            default: []
        },
        areas: {
            type: Array,
            default: []
        },
        instructors: {
            type: Array,
            default: []
        },
        physicalrooms: {
            type: Array,
            default: []
        },
        virtualrooms: {
            type: Array,
            default: []
        },

    },
    data() {
        return {
            fetching: false,
            booking: null,
            editProgram: false,
            editDate: false,
            editTime: false,
            editArea: false,
            editInstructor: false,
            editPhysicalRoom: false,
            editLink: false,

            links: [],
            es: es,
        }
    },
    filters: {
        toLocalDateString(value) {
            return moment(value).toDate().toLocaleDateString()
        },
        toLocalTimeString(value) {
            return moment(value).toDate().format('HH:mm')
        },
    },
    computed: {
        sortedPrograms() {
            return this.programs.sort((a, b) => a.mnemonic > b.mnemonic);
        },
        sortedAreas() {
            return this.areas.sort((a, b) => a.mnemonic > b.mnemonic);
        },

        sortedPhysicalRooms() {
            console.log(this.physicalrooms)
            return this.physicalrooms.sort((a, b) => a.mnemonic > b.mnemonic);
        },

        selectableInstructors() {
            var instructorAreasList = []
            var instructorList = [];

            instructorAreasList =(this.booking.area == null
                ? this.instructors
                : this.instructors.filter(
                      (ia) => ia.area_id == this.booking.area.id
                  )
            ).sort((a, b) => a.instructor.mnemonic > b.instructor.mnemonic)



            instructorAreasList.forEach ( (instructor) => {
                instructorList.push ( {id: instructor.instructor.id,
                                        name: instructor.instructor.name,
                                        mnemonic: instructor.instructor.mnemonic,

                })
            })
            console.log("selectable instructors", instructorList)
            return instructorList;

        },

        selectableLinks() {

            var linksList = this.links
            var returnList = []

            linksList.forEach ( (link) => {
                returnList.push ( {id: link.virtual_meeting_link_id,
                                link: link.virtual_meeting_link,
                                password: link.password,
                                virtual_room_id: link.virtual_room_id,
                                virtual_room_name: link.virtual_room_name,
                                waiting_room: link.waiting_room,

                        })
            })

            console.log("links", this.links)

            return returnList
           // return this.links.sort((a, b) => a.mnemonic > b.mnemonic);

        },



    },
    async mounted() {
        await this.fetchBooking()
        await this.fetchLinksForThisProgram()
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

            //change start_time and end_time format

            this.booking.start_time = this.formatTime(this.booking.start_time)
            this.booking.end_time = this.formatTime(this.booking.end_time)

            console.log(this.booking)



        },

        async fetchLinksForThisProgram() {

            try {
                this.links = await virtualMeetingLinksApi.get(this.booking.program.id)
            } catch (e) {
                console.log(e)
            } finally {
                this.fetching = false
            }

        },

        onProgramNameClick() {
            this.resetEditSelection()
            this.editProgram = true
              console.log(this.booking)
              console.log("selectable instructors",this.selectableInstructors)

        },
        async onProgramChange(programId) {
            let program = this.programs.filter(p => p.id == programId)
            if (program.length == 0) {
                this.booking.program = null
            } else {
                this.booking.program = program[0]
            }
            await this.fetchLinksForThisProgram()

            this.resetEditSelection()

        },
        onDateClick() {
            this.resetEditSelection()
            this.editDate = true
        },
        onDateCalendarClosed(value) {
            //this.booking.booking_date = value
            this.resetEditSelection()
        },

        onTimeClick() {
            this.resetEditSelection()
            this.editTime = true
        },

        onAreaClick(){
            this.resetEditSelection()
            this.editArea = true
        },

        onAreaChange(areaId){
            let area = this.areas.filter(a => a.id == areaId)
            if (area.length == 0) {
                this.booking.area = null
            } else {
                this.booking.area = area[0]
            }
            this.booking.instructor = null
            this.resetEditSelection()


        },

        onInstructorClick(){
            this.resetEditSelection()
            this.editInstructor = true

        },

        onInstructorChange(instructorId){

            let instructor = this.selectableInstructors.filter(i => i.id == instructorId)

            if (instructor.length == 0) {
                this.booking.instructor = null
            } else {
                this.booking.instructor = instructor[0]
            }
            this.resetEditSelection()

        },

        onPhysicalRoomClick(){
            this.resetEditSelection()
            this.editPhysicalRoom = true

        },

        onPhysicalRoomChange(physicalroomId){
            let physicalroom = this.physicalrooms.filter(p => p.id == physicalroomId)
            if (physicalroom.length == 0) {
                this.booking.physical_room = null
            } else {
                this.booking.physical_room = physicalroom[0]
            }
            this.resetEditSelection()
        },

        async onVirtualRoomClick(){
            await this.fetchLinksForThisProgram()
            this.resetEditSelection()
            this.editLink = true

        },

        onLinkChange(linkId){
            let link = this.selectableLinks.filter(l => l.id == linkId)
            if (link.length == 0) {
                this.booking.virtual_meeting_link = null
            } else {
                this.booking.virtual_meeting_link = link[0]
            }
            //must change virtual Room associated with the new selected link



            this.resetEditSelection()

        },

        resetEditSelection() {
            this.editProgram = false
            this.editDate = false
            this.editTime = false
            this.editArea=false
            this.editInstructor=false
            this.editPhysicalRoom=false
            this.editLink =false
        },

        formatTime (value){
            return moment(value).toDate().format('HH:mm')
        },




    }
}
</script>
