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
                :clearable="false"
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

            <div  @click="onAreaClick">
                <font-awesome-icon icon="book"/> Área
                <div class="ml-5" v-if="!editArea" >
                  {{ (!booking.area) ? '-' : booking.area.name }}
                </div>
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

            <div @click="onInstructorClick" >
                <font-awesome-icon icon="chalkboard-teacher"/> Profesor
                <div class="ml-5" v-if="!editInstructor" >
                    {{ (!booking.instructor) ? '-' : booking.instructor.name }}
                </div>
            </div>


            <v-select
                v-if="editInstructor"
                :options="selectableInstructors"
                @input="onInstructorChange"
                label="mnemonic"
                v-model="booking.instructor"
                :reduce="(si) => (!si ? null : si.id)"
            />

            <div  @click="onPhysicalRoomClick" >
                <font-awesome-icon icon="chalkboard"/> Aula Física
                <div class="ml-5" v-if="!editPhysicalRoom">
                    {{ (!booking.physical_room) ? '-' : booking.physical_room.name }}
                </div>
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
                <font-awesome-icon icon="link"/> Aula Virtual
                <div class="ml-5" v-if="!editLink &&  booking.virtual_meeting">
                        <a :href="booking.virtual_meeting.link" target="_blank">
                            {{ booking.virtual_meeting.link }}
                        </a>
                        <!-- <br> -->
                       <div v-if="booking.virtual_meeting.password">
                           PASS: {{ booking.virtual_meeting.password }}
                        </div>
                        <!-- <br> -->
                        {{ booking.virtual_meeting ? booking.virtual_meeting.virtual_room_name : "-" }}
                        <!-- <br> -->
                        <div v-if="booking.virtual_meeting.waiting_room">
                            <font-awesome-icon icon="hourglass-start"/> Sala de espera
                        </div>

                </div>
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
                        <li v-for="sp in booking.support_people" v-bind:key="sp.support_person_id">
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
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-danger pull-right" @click="onDeleteClick">Eliminar</button>
                    <button v-if="editing" class="btn btn-success pull-right mr-3" @click="onSaveClick">Guardar</button>
                </div>
            </div>
            <div class="row mt-5">
                <div class=col-md-12>
                    <a href="#" @click="onCloneClick" class="pull-right">Duplicar sesión</a>
                </div>
            </div>
        </div>
        <modal name="addMeeting" height="auto">
            <add-meeting :virtualrooms = "virtualrooms" :program = "selectedProgram" @on-add-link="onAddLinkHandler"></add-meeting>
        </modal>

        <modal name="deleteConfirmation" height="auto">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="col-md-12">
                            <p>
                                ¿Está seguro que desea eliminar esta sesión?
                            </p>
                            <div>
                                <button class="btn btn-default pull-right" @click="doNotDelete">Cancelar</button>
                                <button class="btn btn-danger pull-right" @click="doDelete">Eliminar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </modal>

        <modal name="cloneBooking" height="auto">
            <booking-clone :booking="booking"></booking-clone>
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

import BookingClone from './BookingClone.vue'


export default {
    components: {
        vSelect,
        Multiselect,
        Datepicker,
        AddMeeting,
        VModal,
        BookingClone
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

            saving: false,
            editing: false,
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

            var b= []

            try {
                ////this.booking = await bookingsApi.get(this.bookingId)
                b = await bookingsApi.get(this.bookingId)
                console.log("Booking from DB",b)

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
                                    'id': null,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'topic': b.topic,
                            'booking_date': b.booking_date,
                            'start_time': this.formatTime(b.start_time),
                            'end_time': this.formatTime(b.end_time),
                            'area': (b.area) ? {
                                    'id': b.area.id,
                                    'name': b.area.name,
                                    'mnemonic': b.area.mnemonic,
                                }: {
                                    'id': null,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'instructor': (b.instructor) ? {
                                    'id': b.instructor.id,
                                    'name': b.instructor.name,
                                    'mnemonic': b.instructor.mnemonic,
                                } : {
                                    'id': null,
                                    'name': '',
                                    'mnemonic': '',
                                },
                            'physical_room': (b.physical_room) ? {
                                    'id': b.physical_room.id,
                                    'mnemonic': b.physical_room.mnemonic,
                                    'name': b.physical_room.name,
                                }: {
                                    'id': null,
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
                                    link_id: null,
                                    link: '',
                                    password:  '',
                                    waiting_room: 0,
                                    virtual_room_id: 0,
                                    virtual_room_name: '',
                                    virtual_room_mnemonic: ''
                                },

            }

            var supportPeopleList = []

            b.booking_support_persons.forEach(function (bsp) {
                supportPeopleList.push({
                                        'support_person_id': bsp.support_person_id,
                                        'name': bsp.support_person.name,
                                        'mnemonic': bsp.support_person.mnemonic,
                                        'role': bsp.support_role,
                                        'type': bsp.support_type,
                })
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
            console.log("this booking", this.bookingId)
            var self = this;

            var supportList = []

            this.booking.support_people.forEach(function (bsp) {

                var selectedItems = self.selectableSupportPeople.filter(
                    (selSp) =>
                        selSp.support_person_id == bsp.support_person_id &&
                        selSp.role == bsp.role &&
                        selSp.type == bsp.type
                );
               supportList.push(selectedItems[0])

            });

            console.log(this.selectedSupportPeople)
                this.selectedSupportPeople = supportList
        },

        onProgramNameClick() {
            this.resetEditSelection()
            this.editProgram = true
            this.editing=true
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
            }

            if (!this.isMeeting){
                await this.fetchLinksForThisProgram()
            }

            this.resetEditSelection()

            //reset virtual meeting link info
            this.booking.virtual_meeting = null
            this.editLink = true

            this.editing=true

        },
        onDateClick() {
            this.resetEditSelection()
            this.editDate = true
            this.editing=true
        },
        onDateCalendarClosed(value) {

            this.resetEditSelection()
            this.editing=true
        },

        onTimeClick() {
            this.resetEditSelection()
            this.editTime = true
            this.editing=true
        },

        onAreaClick(){
            this.resetEditSelection()
            this.editArea = true
            this.editing=true
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
            this.editing=true


        },

        onInstructorClick(){
            this.resetEditSelection()
            this.editInstructor = true
            this.editing=true

        },

        onInstructorChange(instructorId){

            let instructor = this.selectableInstructors.filter(i => i.id == instructorId)

            if (instructor.length == 0) {
                this.booking.instructor = null
            } else {
                this.booking.instructor = instructor[0]
            }
            this.resetEditSelection()
            this.editing=true

        },

        onPhysicalRoomClick(){
            this.resetEditSelection()
            this.editPhysicalRoom = true
            this.editing=true

        },

        onPhysicalRoomChange(physicalroomId){
            let physicalroom = this.physicalrooms.filter(p => p.id == physicalroomId)
            if (physicalroom.length == 0) {
                this.booking.physical_room = null
            } else {
                this.booking.physical_room = physicalroom[0]
            }
            this.resetEditSelection()
            this.editing=true
        },

        async onVirtualRoomClick(){
            this.resetEditSelection()
            this.editLink = true
            this.editing=true

            if (this.isMeeting == true){
                this.editLink = false
                this.$modal.show("addMeeting")
            }

        },

        onLinkChange(linkId){


           let link = this.selectableLinks.filter(l => l.link_id == linkId)

           if (link.length == 0) {
                this.booking.virtual_meeting = null

            } else {
                this.booking.virtual_meeting = link[0]

            }
            this.resetEditSelection()
            this.editing=true

        },

        onSupportPeopleClick(){
            this.resetEditSelection()
            this.loadSelectedSupportPeople()
            this.editSupportPeople = true
            this.editing=true

        },

        onSupportPeopleChange(){
           this.booking.support_people = this.selectedSupportPeople
           this.editing=true

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

        onAddLinkHandler(linkObj) {
            this.isMeeting = false
         //   this.booking.virtual_meeting.
            this.$modal.hide('addMeeting')
        },

        async onDeleteClick() {
            this.$modal.show('deleteConfirmation')

        },

        async doDelete(){

            try {
                await bookingsApi.delete(this.bookingId)
                this.$emit('booking-delete', {
                    start: this.booking.booking_date,
                })
                 this.$modal.hide('deleteConfirmation')
            }
            catch (e) {
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude eliminar la sesión"
                });
            }
        },

        doNotDelete(){
            this.$modal.hide('deleteConfirmation')
        },

        async onSaveClick () {

            this.saving = true
            this.editing=false

             try {
                var bookingObj = {
                    booking_date: moment(this.booking.booking_date).toDate(),
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

                console.log("Saving", bookingObj)


                var responseData = await bookingsApi.update(
                        this.bookingId,
                        {
                            newBooking: bookingObj,
                        }
                    );

                this.$emit('booking-save', {
                    start: this.booking.booking_date,
                })

                this.$notify({
                    group: "notificationGroup",
                    type: "success",
                    title: "Registro guardado exitosamente.",
                });

            } catch (e) {
                console.log(e)
                console.log(e.response.data);
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error",
                    text: e.response.data.errorMessage,
                });
            } finally {
                this.saving = false;
            }

        },
        onCloneClick () {
            this.$modal.show('cloneBooking');
        }

    }
}
</script>
