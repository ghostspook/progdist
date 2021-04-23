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

            <div @click="onVirtualRoomClick">
                <font-awesome-icon icon="link"/> Link
            </div>
             <div v-if="!editLink &&  booking.virtual_meeting">

                    <a :href="booking.virtual_meeting.link" target="_blank">
                        {{ booking.virtual_meeting.link }}
                    </a>
                    <br>
                    PASS: {{ booking.virtual_meeting.password }}
                    <br>
                    {{ booking.virtual_meeting ? booking.virtual_meeting.virtual_room_name : "-" }}
                    <br>
                    <span v-if="booking.virtual_meeting.waiting_room">
                        <font-awesome-icon icon="hourglass-start"/> Sala de espera
                    </span>

            </div>

            <v-select
                v-if="editLink"
                id="virtualmeetinglink"
                :options="selectableLinks"
                @input="onLinkChange"
                label="link"
                v-model="booking.virtual_meeting"
                :reduce="(v) => (!v ? null : v.link_id)"
            />

            <div v-if="!editSupportPeople"  @click="onSupportPeopleClick">
            <font-awesome-icon  icon="users"/>
                Equipo de soporte
                <ul>
                        <li v-for="sp in booking.support_people" v-bind:key="sp.id">
                            {{ sp.name }} -
                            {{ sp.role | toSupportRoleText }}
                            ({{ sp.type | toSupportTypeText }})
                        </li>
                </ul>
            </div>

            <div  v-if="editSupportPeople" >
                <font-awesome-icon  icon="users"/>
                    Equipo de soporte
                    <multiselect
                        id="supportPeople"
                        v-model="selectedSupportPeople"
                        :options="selectableSupportPeople"
                        @input="onSupportPeopleChange"
                        track-by="label"
                        label="label"
                        :multiple="true"
                        :taggable="true"
                        :showLabels="true"
                        :hide-selected="true"
                    ></multiselect>
            </div>
        </div>
        <modal name="addMeeting" height="auto">
            <add-meeting :virtualrooms = "virtualrooms" :program = "selectedProgram" @on-add-link="onAddLinkHandler"></add-meeting>
        </modal>
    </div>
</template>

<script>
import bookingsApi from '../services/booking'
import virtualMeetingLinksApi from '../services/programvirtualmeetinglink'
import moment from 'moment'
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import Datepicker from "vuejs-datepicker";
import { en, es } from "vuejs-datepicker/dist/locale";

import AddMeeting from './AddMeeting.vue';
import VModal  from "vue-js-modal";


export default {
    components: {
        vSelect,
        Multiselect,
        Datepicker,
        AddMeeting,
        VModal,
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
        selectableSupportPeople: {
            type: Array,
            default: []
        },

    },
    data() {
        return {
            fetching: false,

            booking: {},
            editProgram: false,
            editDate: false,
            editTime: false,
            editArea: false,
            editInstructor: false,
            editPhysicalRoom: false,
            editLink: false,
            editSupportPeople: false,
            isMeeting: true,



            links: [],
            selectedVirtualMeeting : [],
            selectedSupportPeople: [],
            selectedProgram: 0,
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

        toSupportRoleText(value) {
            switch (value) {
                case 1:
                    return 'Coord. Acad.'
                case 2:
                    return 'Soprte. Acad.'
                case 3:
                    return 'Soprte. TI'
                default:
                    return '?'
            }
        },

        toSupportTypeText(value) {
            switch (value) {
                case 0:
                    return 'Físico'
                case 1:
                    return 'Virtual'
                default:
                    return '?'
            }
        }
    },
    computed: {


        sortedPrograms() {
            return this.programs.sort((a, b) => a.mnemonic > b.mnemonic);
        },
        sortedAreas() {
            return this.areas.sort((a, b) => a.mnemonic > b.mnemonic);
        },

        sortedPhysicalRooms() {
            return this.physicalrooms.sort((a, b) => a.mnemonic > b.mnemonic);
        },
        sortedVirtualRooms() {
            return this.virtualrooms.sort((a, b) => a.mnemonic > b.mnemonic);
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
            var selectableLinksList = []

            linksList.forEach ( (link) => {
                selectableLinksList.push ({
                                link_id: link.virtual_meeting_link_id,
                                link: link.virtual_meeting_link,
                                password: link.password,
                                waiting_room: link.waiting_room,
                                virtual_room_id: link.virtual_room_id,
                                virtual_room_name: link.virtual_room_name,
                                virtual_room_mnemonic: link.virtual_room_mnemonic,
                        })
            })



            return selectableLinksList
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

            var b= []

            try {
                ////this.booking = await bookingsApi.get(this.bookingId)
                b = await bookingsApi.get(this.bookingId)

            } catch (e) {
                console.log(e)
            } finally {
                this.fetching = false
            }

             //summarize Booking Info

            this.booking = {
                            'booking_id': b.id,
                            'program': (b.program) ? {
                                    'id': b.program.id,
                                    'name': b.program.name,
                                    'mnemonic': b.program.mnemonic,
                                } : {
                                    'id': 0,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'booking_date': b.booking_date,
                            'start_time': this.formatTime(b.start_time),
                            'end_time': this.formatTime(b.end_time),
                            'area': (b.area) ? {
                                    'id': b.area.id,
                                    'name': b.area.name,
                                    'mnemonic': b.area.mnemonic,
                                }: {
                                    'id': 0,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'instructor': (b.instructor) ? {
                                    'id': b.instructor.id,
                                    'name': b.instructor.name,
                                    'mnemonic': b.instructor.mnemonic,
                                } : {
                                    'id': 0,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'physical_room': (b.physical_room) ? {
                                    'id': b.physical_room.id,
                                    'mnemonic': b.physical_room.mnemonic,
                                    'name': b.physical_room.name,
                                }: {
                                    'id': 0,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'virtual_meeting': (b.virtual_meeting_link) ? {
                                    link_id: b.virtual_meeting_link.id,
                                    link: b.virtual_meeting_link.link,
                                    password:  b.virtual_meeting_link.password,
                                    waiting_room: b.virtual_meeting_link.waiting_room,
                                    virtual_room_id: b.virtual_meeting_link.virtual_room.id,
                                    virtual_room_name: b.virtual_meeting_link.virtual_room.name,
                                    virtual_room_mnemonic: b.virtual_meeting_link.virtual_room.mnemonic,
                                } : {
                                    link_id: 0,
                                    link: '',
                                    password:  '',
                                    waiting_room: 0,
                                    virtual_room_id: 0,
                                    virtual_room_name: '',
                                    virtual_room_mnemonic: ''
                                },

            }

            var supportPeopleList = []
            var supportPerson = {}

            b.booking_support_persons.forEach(function (bsp) {
                supportPerson.id = bsp.support_person_id
                supportPerson.name = bsp.support_person.name
                supportPerson.mnemonic = bsp.support_person.mnemonic
                supportPerson.role = bsp.support_role
                supportPerson.type = bsp.support_type
             //   supportPerson.label = bsp.role + " - " + bsp.support_person.mnemonic + " - " + bsp.support_type

                supportPeopleList.push(supportPerson)
            })

            this.booking.support_people = supportPeopleList

            //end of summarized Booking Info
            console.log("Summarized Booking",this.booking)
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

        loadSelectedSupportPeople(){
            var self = this;

            var supportList = []

            this.booking.support_people.forEach(function (bsp) {

                var selectedItems = self.selectableSupportPeople.filter(
                    (selSp) =>
                        selSp.id == bsp.id &&
                        selSp.role == bsp.role &&
                        selSp.type == bsp.type
                );
               supportList.push(selectedItems[0])

            });
                this.selectedSupportPeople = supportList
        },

        onProgramNameClick() {
            this.resetEditSelection()
            this.editProgram = true
            console.log(this.booking)


        },
        async onProgramChange(programId) {
         this.selectedProgram = programId
           let program = this.programs.filter(p => p.id == programId)
            if (program.length == 0) {
                this.booking.program = null
            } else {
                this.booking.program = program[0]
            }

            if (this.booking.program.mnemonic == "(REUNIÓN)") {
                this.isMeeting = true
                this.$modal.show('addMeeting')
            }

            if (!this.isMeeting){
                await this.fetchLinksForThisProgram()
            }

            this.resetEditSelection()

            //reset virtual meeting link info
            this.booking.virtual_meeting = null
            this.editLink = true

            console.log("Booking", this.booking)

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
            //await this.fetchLinksForThisProgram()
            this.resetEditSelection()
            this.editLink = true
               console.log("Booking Links", this.booking.virtual_meeting)

        },

        onLinkChange(linkId){


           let link = this.selectableLinks.filter(l => l.link_id == linkId)

           if (link.length == 0) {
                this.booking.virtual_meeting = null

            } else {
                this.booking.virtual_meeting = link[0]

            }
            this.resetEditSelection()

        },

        onSupportPeopleClick(){
            this.resetEditSelection()
            console.log("Booking Support People--->", this.booking.support_people)

           this.loadSelectedSupportPeople()
            this.editSupportPeople = true

        },

        onSupportPeopleChange(){
           console.log("People changed", this.selectedSupportPeople)
           this.booking.support_people = this.selectedSupportPeople

          //  this.resetEditSelection()

        },

        resetEditSelection() {
            this.editProgram = false
            this.editDate = false
            this.editTime = false
            this.editArea=false
            this.editInstructor=false
            this.editPhysicalRoom=false
            this.editLink =false
            this.editSupportPeople = false
        },

        formatTime (value){
            return moment(value).toDate().format('HH:mm')
        },

        onAddLinkHandler() {
             this.$modal.hide('addMeeting')

        }


    }
}
</script>
