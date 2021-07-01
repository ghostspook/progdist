<template>
    <div>
        <div class="row">
            <div class="col-md-4 ml-4 mt-4">
                Semana del:
                <input type="Date" v-model="startDate" @change="onDateChange"/>
            </div>
            <div class="col-md-6">
                Ordenar por:
                <multiselect
                    id="calendarOrdering"
                    v-model="selectedOrdering"
                    :options="selectableOrdering"
                    @input="onOrderingChange"
                    track-by="orderBy"
                    label="orderBy"
                    :multiple="true"
                    :taggable="true"
                    :showLabels="true"
                    :hide-selected="true"
                ></multiselect>
            </div>
        </div>

        <div class="row mt-4" >
            <!-- <div class="col-md-12 ml-2" v-html="basicCalendar" :key="calendarKey">

            </div> -->

            <div class="col-md-12 ml-2" v-for="(day,index) in daysOfWeek" :key="index" >

                <table class="table table-striped">
                    <thead class="thead-dark" >
                        <tr>
                            <th colspan="3">  {{ nextDay(from,day) | toDayNameHeader }}  </th>
                        </tr>
                        <tr>
                            <th  v-if="canCreateAndEditBookings" style="width:2%"> Editar</th>
                            <th> Fecha </th>
                            <th> Programa</th>
                            <th> Área </th>
                            <th> Profesor </th>
                            <th> Aula Física </th>
                            <th> Aula Virtual </th>
                            <th> Inicia </th>
                            <th> Termina </th>
                            <th> Link </th>
                            <th> Contraseña </th>
                            <th> Soporte </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="booking in  thisDayBookings(day)" :key="booking.booking_id" >
                            <td v-if="canCreateAndEditBookings" >
                                <a href="#" class="edit btn btn-sm btn-primary"

                                    @click="onBookingEdit(booking.booking_id)">
                                        <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td >
                                {{ nextDay(from,day) | toDateString }}


                            </td>
                            <td  >
                                <span :class="programClass(booking.program_class)">
                                    {{ programWithTopic (booking.program, booking.topic ) }}
                                </span>
                            </td>
                            <td >
                                {{ booking.area }}
                            </td>
                            <td >
                                {{ booking.instructor_name }}
                            </td>
                            <td >
                                {{ booking.physical_room }}
                            </td>
                            <td >
                                <div>
                                    {{ booking.virtual_room }}
                                </div>
                                <div class="mt-2 alert alert-danger" v-if="booking.virtual_room_capacity>100">
                                    ¡Requiere cupo
                                    {{booking.virtual_room_capacity}}!
                                </div>
                            </td>
                            <td >
                                {{ formatBookingTime(booking.start_time) }}
                            </td>
                            <td >
                                {{ formatBookingTime(booking.end_time) }}
                            </td>
                            <td >
                                {{ booking.link }}
                            </td>
                            <td >
                                {{ booking.password }}
                            </td>
                            <td v-html="booking.support" >

                            </td>
                        </tr>
                        <!-- CONSTRAINTS -->
                        <tr v-for="constraint in  thisDayConstraints(day)" :key="constraint.id">
                            <td colspan="4">
                                <div class="vuecal__event dark_gray">
                                    <h5> BLOQUEO {{ constraint.instructor.name}} </h5>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                @booking-clone="onBookingClone"
                :clonable= "false"
                :deletable= "false"

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

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

import bookingsApi from '../services/booking'
import instructorsApi from '../services/instructor'
import instructorsAreaApi from "../services/instructorarea";

import programsApi from "../services/program";
import areasApi from "../services/area";
import physicalroomsApi from "../services/physicalroom";
import virtualRoomsApi from "../services/virtualroom";
import supportPeopleApi from "../services/supportperson";

import moment from 'moment'

import userApi from '../services/user'

import BookingEditor from './BookingEditor.vue'


const ROLE_COORD = 1;
const ROLE_ACAD = 2;
const ROLE_TI = 3;

const SUPPORT_TYPE_PHYSICAL = 0;
const SUPPORT_TYPE_VIRTUAL = 1;





export default {
    components: {
        Multiselect,
        BookingEditor,

    },
    data() {
        return {
            user:null,
            from: null,
            displayEventDetails: false,
            daysOfWeek: [0,1,2,3,4,5,6],
            bookings: [],
            instructorConstraints: [],
            startDate: moment().format("YYYY-MM-DD"),
            calendarKey: 0,
            selectedOrdering: [],
            selectableOrdering: [],

            selectedBookingId: 0,
            supportPeopleList: [],
        }
    },

    filters: {
        toDayNameHeader(from) {
            moment.locale("es");
            return moment(from).format("dddd").toUpperCase()  + " " + moment(from).format("DD-MMM-YYYY")

        },


        toDateString(bookingDate){
            return moment(bookingDate).format("YYYY-MM-DD")
        },



    },
    computed: {

        canCreateAndEditBookings() {
            return (!this.user) ? false : this.user.authorized_account.can_create_and_edit_bookings == 1
        },

        selectableSupportPeople() {
            var returnList = [];
            this.supportpeopleList.forEach((person) => {
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



    },
    async mounted() {

        await this.getUserInfo()

        await this.fetchPrograms()
        await this.fetchAreas()
        await this.fetchInstructors()
        await this.fetchPhysicalRooms()
        await this.fetchVirtualRooms()
        await this.fetchSupportPeople()

        this.from = moment(this.startDate).startOf('isoWeek').format('YYYY-MM-DD')

        await this.fetchBookings()

        await this.fetchInstructorConstraints()
        console.log("Constraints", this.instructorConstraints)

        this.selectableOrdering.push({orderBy: "Aula Física"})
        this.selectableOrdering.push({orderBy: "Aula Virtual"})
        this.selectableOrdering.push({orderBy: "Hora de Inicio"})
        this.selectableOrdering.push({orderBy: "Profesor"})
        this.selectableOrdering.push({orderBy: "Programa"})




        console.log("this bookings", this.bookings)







    },
    methods: {

        toggle() {
            this.displayEventDetails = !this.displayEventDetails;
        },

        async getUserInfo (){
              if (!this.user) {
                this.user = await userApi.getMyUser()
            }

        },

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
                this.instructors = await instructorsAreaApi.getAll()
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
                this.supportpeopleList = await supportPeopleApi.getAll();
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


        thisDayBookings(days) {
            var from=this.from
            var thisDay = moment(from).add(days,'days').format('YYYY-MM-DD')
            console.log("To This Day Booking",this.bookings.filter( (b) => moment(b.booking_date).isSame(thisDay,'day')) )
            return this.bookings.filter( (b) => moment(b.booking_date).isSame(thisDay,'day'))

        },

        thisDayConstraints(days){
            var from=this.from
            var thisDay = moment(from).add(days,'days').format('YYYY-MM-DD')

            return  this.instructorConstraints.filter( (constraint) => moment(constraint.from).isSameOrBefore(thisDay,'day') &&
                                                                        moment(constraint.to).isSameOrAfter(thisDay,'day')
                                                                        )
        },

        programClass (pClass){
            var classPrefix = "vuecal__event "
            return pClass ? classPrefix + pClass : ""

        },

        programWithTopic (program, topic){
            return program +  ( !topic ? "" : " - "  + topic )
        },

        nextDay (fromDay, days){
            return moment(fromDay).add(days,'days').format('YYYY-MM-DD')
        },
        onBookingEdit(b){
            if (!b) {
                return;
            }
            this.selectedBookingId = b
            this.displayEventDetails = true


        },


        async fetchBookings() {
            var from = null
            var to = null
            var orderBy={}
            from = moment(this.startDate).startOf('isoWeek')
            to = moment(this.startDate).endOf('isoWeek')
            orderBy = JSON.stringify(this.selectedOrdering)


            console.log("Estos bookings", this.bookings)
            this.bookings = await bookingsApi.getByWeek(from.format('YYYY-MM-DD'), to.format('YYYY-MM-DD'), orderBy)

        },

        async fetchInstructorConstraints (){
            var from = null
            var to = null

            from = moment(this.startDate).startOf('isoWeek')
            to = moment(this.startDate).endOf('isoWeek')

            console.log("from Constraint", from)

            this.instructorConstraints = await instructorsApi.getInstructorConstraints(from.format('YYYY-MM-DD'), to.format('YYYY-MM-DD'))


        },

        async onBookingDelete (e) {
            this.toggle()
            this.selectedBookingId = 0
            await this.onDateChange()
        },
        async onBookingSave (e) {
            this.toggle()
            this.selectedBookingId = 0
            await this.onDateChange()
        },

        async onBookingClone (e) {
            this.toggle()
            this.selectedBookingId = 0
            await this.onDateChange()
        },





        async  onDateChange(){
            this.from = moment(this.startDate).startOf('isoWeek').format('YYYY-MM-DD')
            console.log("Nueva Fecha",this.startDate)
            await this.fetchBookings()
            await this.fetchInstructorConstraints()
            console.log("Constraints", this.instructorConstraints)
            console.log("Bookings", this.bookings)
            this.forceRerender()
        },

        formatBookingTime(value){
            return moment(value).toDate().format("HH:mm")
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
        },

        async supportPeople (){
            var supportStr = ""
            var sp= []



            console.log("personas de soporte",sp)

            sp.forEach( p => {

                    supportStr = supportStr + "<p>" + "<b>" + this.toSupportRoleText (p.support_role) +
                    " : " + "</b>" + p.support_person.name + " - " + this.toSupportTypeText (p.support_type)
                    + "</p>"

                })

           console.log("support",supportStr)
            return supportStr
        },

        forceRerender(){
            this.calendarKey +=1
        },

        async onOrderingChange (){

                await this.fetchBookings()


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
  overflow: scroll;
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
.vuecal__event.dark_orange {background-color: rgba(124, 71, 21, 0.9);border: 1px solid rgb(124, 71, 21, 0.9);color: #fff;}

.vuecal__event.green {background-color: rgba(164, 230, 210, 0.9);border: 1px solid rgb(144, 210, 190);color:black;}
.vuecal__event.dark_green {background-color: rgba(3, 75, 53, 0.9);border: 1px solid rgb(3, 75, 53, 0.9);color:#fff;}

.vuecal__event.red {background-color: rgba(255, 102, 102, 0.9);border: 1px solid rgb(235, 82, 82);color: #fff;}
.vuecal__event.dark_red {background-color: rgba(88, 8, 8, 0.9);border: 1px solid rgb(88, 8, 8, 0.9);color: #fff;}

.vuecal__event.blue {background-color: rgba(102, 181, 255, 0.9);border: 1px solid rgb(102, 181, 255);color: #fff;}
.vuecal__event.dark_blue {background-color: rgba(1, 48, 102, 0.9);border: 1px solid rgb(1, 48, 102);color: #fff;}

.vuecal__event.fucsia {background-color: rgba(201, 40, 193, 0.9);border: 1px solid rgb(201, 40, 193, 0.9);color: #fff;}
.vuecal__event.wine {background-color: rgb(53, 2, 2);border: 1px solid rgb(53, 2, 2);color: #fff;}
.vuecal__event.purple {background-color: rgba(75, 14, 72, 0.9);border: 1px solid rgb(75, 14, 72, 0.9);color: #fff;}
.vuecal__event.mamey {background-color: rgba(250, 169, 93, 0.9);border: 1px solid rgb(250, 169, 93, 0.9);color: #fff;}

.vuecal__event.dark_gray {background-color: rgba(77, 69, 68, 0.932);border: 1px solid rgb(250, 169, 93, 0.9);color: #fff;}

.vuecal__event {background-color: rgba(182, 191, 201, 0.9);border: 1px solid rgb(182, 191, 201);color: black;}

</style>

