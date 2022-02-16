<template>
    <div>
        <h4 v-if="fetching">Cargando...</h4>
        <div v-if="booking">
            <h4 class="p-3 mb-2 bg-success text-white" v-if="multiEdit">Editando todas las sesiones seleccionadas</h4>
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
            <div v-if="isMeeting">
                <div v-if="!editTopic" @click="onTopicClick">
                   Tema: {{ booking.topic }}
                </div>
                <div v-if="editTopic">
                    <input type="text" v-model="booking.topic">
                </div>

            </div>

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

            <div v-if="!multiEdit && !editTime" @click="onTimeClick">
                <font-awesome-icon icon="clock"/>
                {{ booking.start_time }} -
                {{ booking.end_time  }}
            </div>


            <div  v-if="!multiEdit && editTime">
                <label for="startTime">Inicia: </label>
                <input v-model="booking.start_time" id="startTime" type="time" min="06:00" max="23:55" required>

                <label for="endTime">Termina: </label>
                <input v-model="booking.end_time" id="endTime" type="time" min="06:00" max="23:55" required>

            </div>

            <div v-if="!multiEdit" @click="onAreaClick">
                <font-awesome-icon icon="book"/> Área
                <div class="ml-5" v-if="!editArea" >
                  {{ (!booking.area) ? '-' : booking.area.name }}
                </div>
            </div>

            <v-select
                v-if="!multiEdit && editArea"
                id="areas"
                :options="sortedAreas"
                @input="onAreaChange"
                label="mnemonic"
                v-model="booking.area"
                :reduce="(sa) => (!sa ? null : sa.id)"
            />

            <div v-if="!multiEdit" @click="onInstructorClick" >
                <font-awesome-icon icon="chalkboard-teacher"/> Profesor
                <div class="ml-5" v-if="!editInstructor" >
                    {{ (!booking.instructor) ? '-' : booking.instructor.name }}
                </div>
            </div>


            <v-select
                v-if="!multiEdit && editInstructor"
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
                        <div>
                            {{ booking.virtual_meeting ? booking.virtual_meeting.virtual_room_name : "-" }}
                        <!-- <br> -->
                        </div>
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
                    <button v-if="deletable" class="btn btn-danger pull-right" @click="onDeleteClick">Eliminar</button>
                    <button v-if="editing" class="btn btn-success pull-right mr-3" @click="onSaveClick">Guardar</button>
                    
                </div>
            </div>

            <div class="row" v-if="editVirtualRoomCapacity">
                <div class="row mt-3 ml-3">
                    <label> <strong> Capacidad de Aula Virtual </strong></label>
                </div>
                <div class="col-md-12  justify-content-center">

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="regularMeeting" value="100" v-model="booking.virtual_room_capacity" >
                                        <label class="form-check-label" for="regularMeeting">
                                            Regular(100)
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="largeMeeting" value="500" v-model="booking.virtual_room_capacity">
                                        <label class="form-check-label" for="largeMeeting">500</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="veryLargeMeeting" value="1000" v-model="booking.virtual_room_capacity">
                                        <label class="form-check-label" for="veryLargeMeeting">1000</label>
                                    </div>

                </div>


            </div>

            <div class="row" v-if="!editVirtualRoomCapacity" @click="onVirtualRoomCapacityClick">
                 <div class="row mt-3 ml-3">
                    <label> <strong> Capacidad de Aula Virtual: {{ virtualRoomCapacity}} </strong></label>
                </div>
            </div>



            <div v-if="!multiEdit" class="row mt-5">
                <div class=col-md-4>
                    <a href="#" @click="onSplitClick" class="pull-right">
                        <img src="/css/split-min.png" alt="Split Booking">  Split
                    </a>
                </div>
                <div class=col-md-8>
                    <a v-if="clonable" href="#" @click="onCloneClick" class="pull-right">
                        <img src="/css/sheep.png" alt="Clone Booking">  Clonar Sesión
                    </a>
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


        <modal name="cloneBooking" height="auto" >
                <div class="booking-clone-card">
                <booking-clone :booking="booking"
                            @booking-clonning-error="onBookingClonningError"
                            @booking-clonning-success="onBookingClonningSuccess"
                >
                </booking-clone>
                </div>
        </modal>

        <modal name="splitBooking" height="auto" >
                <div class="booking-clone-card">
                <booking-splitter :booking="booking"
                        @booking-splitting-error="onBookingSplittingError"
                        @booking-splitting-success="onBookingSplittingSuccess"
                >
                </booking-splitter>
                </div>
        </modal>


        <modal name="checkTime" height="auto">
            <div class="card">
                <h5 class="card-header">
                    <span style="color:red">Error en las horas de la sesión </span>
                </h5>
                <div class="card-body">
                    <div class="form-group">
                        <p> La hora de inicio debe ser anterior a la hora de finalización </p>
                    </div>
                    <div>
                        <button  class="btn btn-success mr-3" @click="onCheckTime">Ok</button>
                    </div>
                </div>
            </div>
        </modal>
        <notifications group="notificationGroup" position="top center" />

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

import BookingSplitter from './BookingSplitter.vue'

const MEETING_MNEMONIC = "(REUNIÓN)";


export default {
    components: {
        vSelect,
        Multiselect,
        Datepicker,
        AddMeeting,
        VModal,
        BookingClone,
        BookingSplitter,
    },
    props: {
        clonable: {
            type: Boolean,
            required: true,
            default: true,
        },
        deletable: {
            type: Boolean,
            required: true,
            default: true,
        },
        multiEdit: {
            type: Boolean,
            required: true,
            default: false,
        },
        selectedBookingsForMultiEditing: {
            type: Array,
            default: [],
        },
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
            editTopic: false,
            editDate: false,
            editTime: false,
            editArea: false,
            editInstructor: false,
            editPhysicalRoom: false,
            editLink: false,
            editSupportPeople: false,
            editVirtualRoomCapacity: false,

            isMeeting: true,

            links: [],
            defaultVirtualMeetingLink: [],
            selectedSupportPeople: [],
            selectedProgram: 0,
            es: es,

            saving: false,
            editing: false,

            virtualRoomCapacity: 100,
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
                                is_default_link: link.is_default_link,
                                waiting_room: link.waiting_room,
                                virtual_room_id: link.virtual_room_id,
                                virtual_room_name: link.virtual_room_name,
                                virtual_room_mnemonic: link.virtual_room_mnemonic,
                        })
            })
            return selectableLinksList
           // return this.links.sort((a, b) => a.mnemonic > b.mnemonic);

        },

        isthereTopic(){
           return (this.booking.topic != null || this.booking.topic !="") ?  true :  false
        },

    },
    async mounted() {

        await this.fetchBooking()
        this.selectedProgram = this.booking.program.id
        
        this.virtualRoomCapacity = this.booking.virtual_room_capacity
        this.checkForMeeting()

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
                                    is_default_link: b.virtual_meeting_link.is_default_link,
                                    password:  b.virtual_meeting_link.password,
                                    waiting_room: b.virtual_meeting_link.waiting_room,
                                    virtual_room_id: b.virtual_meeting_link.virtual_room.id,
                                    virtual_room_name: b.virtual_meeting_link.virtual_room.name,
                                    virtual_room_mnemonic: b.virtual_meeting_link.virtual_room.mnemonic,
                                } : {
                                    link_id: null,
                                    link: '',
                                    is_default_link: false,
                                    password:  '',
                                    waiting_room: 0,
                                    virtual_room_id: 0,
                                    virtual_room_name: '',
                                    virtual_room_mnemonic: ''
                                },
                            'virtual_room_capacity': b.virtual_room_capacity,

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
          //  console.log("Summarized Booking",this.booking)
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
                        selSp.support_person_id == bsp.support_person_id &&
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
            this.editing=true



        },
        async onProgramChange(programId) {
            this.selectedProgram = programId
            let program = this.programs.filter(p => p.id == programId)
            if (program.length == 0) {
                this.booking.program = null
            } else {
                this.booking.program = program[0]
            }

            if (this.booking.program.mnemonic == MEETING_MNEMONIC) {
                this.isMeeting = true
                this.editLink = false
                this.links = []

           }
            else {
                this.isMeeting = false
                this.editLink = true
                await this.fetchLinksForThisProgram()

            }



            this.resetEditSelection()

            this.loadDefaultVirtualMeetingLink()

            this.editing=true

        },
        onTopicClick() {
            this.resetEditSelection()
            this.editTopic = true
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

            this.editing=true


            if (this.isMeeting){
                this.editLink = false
                this.$modal.show("addMeeting")
            }
            else if (!this.isMeeting){
                this.fetchLinksForThisProgram()
                this.editLink = true
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

        onVirtualRoomCapacityClick(){
            this.resetEditSelection()
            this.editVirtualRoomCapacity = true
            this.editing= true

        },


        onSupportPeopleChange(){
           this.booking.support_people = this.selectedSupportPeople
           this.editing=true

        },

        resetEditSelection() {
            this.editProgram = false
            this.editTopic = false
            this.editDate = false
            this.editTime = false
            this.editArea=false
            this.editInstructor=false
            this.editPhysicalRoom=false
            this.editLink =false
            this.editSupportPeople = false
            this.editVirtualRoomCapacity = false
        },

        formatTime (value){
            return moment(value).toDate().format('HH:mm')
        },

        onAddLinkHandler(linkObj) {


            this.selectedProgram = this.booking.program.id


            this.booking.virtual_meeting.link_id = linkObj.link_id
            this.booking.virtual_meeting.link = linkObj.link
            this.booking.virtual_meeting.password = linkObj.password
            this.booking.virtual_meeting.waiting_room =  linkObj.waiting_room
            this.booking.virtual_meeting.virtual_room_id  = linkObj.virtual_room_id
            this.booking.virtual_meeting.virtual_room_name  = linkObj.virtual_room_name
            this.booking.virtual_meeting.virtual_room_mnemonic =  linkObj.virtual_room_mnemonic

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

        async multiUpdate (){
            this.saving= true
            this.editing=false

            for (var i=0;i<this.selectedBookingsForMultiEditing.length;i++){
                try {
                    var bookingObj = {
                        booking_date: moment(this.booking.booking_date).toDate(),
                        program: this.booking.program ? this.booking.program.id : null,
                        topic: this.booking.topic,
                        physicalRoom: this.booking.physical_room ? this.booking.physical_room.id : null,
                        virtualRoom: this.booking.virtual_meeting ? this.booking.virtual_meeting.virtual_room_id : null,
                        supportPeople: this.booking.support_people,
                        link: this.booking.virtual_meeting ? this.booking.virtual_meeting.link_id : null,
                        virtualRoomCapacity: this.booking.virtual_room_capacity,
                    };


                var responseData = await bookingsApi.update(
                        this.selectedBookingsForMultiEditing[i].booking_id,
                        {
                            newBooking: bookingObj,
                        }
                    );
                }
                catch (e) {
                    console.log(e)
                    console.log(e.response.data);
                    this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error",
                    text: e.response.data.errorMessage,
                });
                }

                finally {
                this.saving = false;
                }

            }





        },


        async onSaveClick () {

            if (this.multiEdit){
                this.multiUpdate()
                return
            }

            if ( this.booking.start_time >= this.booking.end_time){
                this.$modal.show('checkTime')
                return
            }

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
                    virtualRoomCapacity: this.booking.virtual_room_capacity,
                  };


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
            if (  this.booking.start_time >= this.booking.end_time){
                this.$modal.show('checkTime')
                return
            }
            this.$modal.show('cloneBooking');
        },

        onSplitClick () {
            if (  this.booking.start_time >= this.booking.end_time){
                this.$modal.show('checkTime')
                return
            }
            this.$modal.show('splitBooking');
        },

        onBookingClonningError(error){
            console.log("Clonning Error",error)
            this.$notify({
                group: "notificationGroup",
                type: "error",
                title: "Error",
                text: error.response.data.errorMessage,
            });
            this.$modal.hide('cloneBooking');
            this.$emit('booking-save', {
                    start: this.booking.booking_date,
                })
        },

        onBookingClonningSuccess(){

            this.$notify({
                group: "notificationGroup",
                type: "success",
                title: "Operación exitosa",
                text: "El evento fue clonado corectamente"
            });
            this.$emit('booking-clone', {
                    start: this.booking.booking_date,
                })


             this.$modal.hide('cloneBooking');


        },

        onBookingSplittingError(error){
            console.log("Splitting Error",error)
            this.$notify({
                group: "notificationGroup",
                type: "error",
                title: "Error",
                text: error.response.data.errorMessage,
            });
            this.$modal.hide('splitBooking');
            this.$emit('booking-save', {
                    start: this.booking.booking_date,
                })
        },

        onBookingSplittingSuccess(){

            this.$notify({
                group: "notificationGroup",
                type: "success",
                title: "Operación exitosa",
                text: "El evento fue dividido (split) satisfactoriamente"
            });
            this.$emit('booking-save', {
                    start: this.booking.booking_date,
                })


             this.$modal.hide('cloneBooking');


        },



        loadDefaultVirtualMeetingLink (){
            //reset virtual meeting link info
            this.booking.virtual_meeting = {
                                    link_id: null,
                                    link: '',
                                    is_default_link: false,
                                    password:  '',
                                    waiting_room: 0,
                                    virtual_room_id: 0,
                                    virtual_room_name: '',
                                    virtual_room_mnemonic: ''
                                }


            var self = this

            if (this.selectableLinks.length > 0) {
                self.defaultVirtualMeetingLink = self.selectableLinks.filter(
                            (link) =>
                                link.is_default_link == true

                        );
                 if(self.defaultVirtualMeetingLink.length > 0){
                    this.booking.virtual_meeting = {
                        link_id: self.defaultVirtualMeetingLink[0].link_id,
                        link: self.defaultVirtualMeetingLink[0].link,
                        is_default_link: self.defaultVirtualMeetingLink[0].is_default_link,
                        password: self.defaultVirtualMeetingLink[0].password,
                        waiting_room: self.defaultVirtualMeetingLink[0].waiting_room,
                        virtual_room_id: self.defaultVirtualMeetingLink[0].virtual_room_id,
                        virtual_room_name: self.defaultVirtualMeetingLink[0].virtual_room_name,
                        virtual_room_mnemonic: self.defaultVirtualMeetingLink[0].virtual_room_mnemonic,
                    }
                    this.$notify({
                            group: "notificationGroup",
                            type: "info",
                            title: "Se aplicó el link predeterminado para el programa seleccionado.",
                            text:   "Tenga en cuenta que este link podría no estar disponible " +
                                "en la fecha de la sesión que está registrando.",
                            duration: -1,
                            width: '50%'
                    });
                 }

            }



        },

        checkForMeeting(){
            if (this.booking.program.mnemonic == MEETING_MNEMONIC){
                this.isMeeting= true
            }
            else{
                this.isMeeting = false
            }

        },

        onCheckTime(){
            this.$modal.hide('checkTime')
        },


    }
}
</script>
<style scoped>

.booking-clone-card {
    overflow: scroll;
}


</style>
