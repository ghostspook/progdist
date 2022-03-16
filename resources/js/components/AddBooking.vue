<template>

   <div class="border border-info rounded-xl" >
       <form>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">

                            <div class="mr-auto p-2">
                                <h5 v-if="bookingId.length<=1"><span>Nueva Sesión </span></h5>
                                <h5 v-if="bookingId.length>1"><span>Está editando varias sesiones </span></h5>
                                <span :class="newBookingError? 'alert alert-danger' :''">  {{ newBookingError }}</span>
                            </div>
                            <div class="p-2">
                                <span class="ml-1 bg-dark text-white btn btn-danger" @click="closeAddBooking()"> Cancelar </span>
                                <span  v-if="!saving" class="ml-1 btn btn-success" @click="saveBooking()"> Guardar </span>
                                <!-- <button v-if="!saving" class="ml-1 btn btn-success" @click="saveBooking()">Guardar</button> -->
                            </div>
                    </div>



                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-2">
                              <label for="bookingDate">Fecha</label>
                            <input type="Date" id="bookingDate" class="form-control" v-model="bookingDate" />
                        </div>

                        <div class="col-md-3" >
                            <label for="program">Programa</label>
                            <select  class="form-control" id="program" v-model="selectedProgram" @change="onProgramChange()">
                                <option :value="null">Ninguno</option>
                                <option v-for="p in sortedPrograms"
                                                v-bind:key="p.id"
                                                :value="p.id"
                                                >
                                                {{ p.mnemonic }}
                                </option>
                            </select>
                        </div>


                        <div class="col-md-3 form-group" >
                            <label for="topic">Tema</label>
                            <input type="text"  class="form-control" id="topic" v-model="topic" />
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="startTime">Inicia</label>
                            <input class="form-control text-center" type="time" id="startTime" v-model="startTime"  />
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="endTime">Termina</label>
                            <input class="form-control text-center" type="time" id="endTime" v-model="endTime" />
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4" >
                            <label for="areas">Área</label>
                            <select  class="form-control" id="areas" v-model="selectedArea" >
                                <option :value="null">Ninguna</option>
                                <option v-for="a in sortedAreas"
                                                v-bind:key="a.id"
                                                :value="a.id"
                                                >
                                                {{ a.mnemonic }} - {{ a.name }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3" >
                            <label for="instructors">Profesor</label>
                            <select  class="form-control" id="instructors" v-model="selectedInstructor" >
                                <option :value="null">Ninguno</option>
                                <option v-for="i in selectableInstructors"
                                                v-bind:key="i.instructor_id"
                                                :value="i.instructor_id"
                                                >
                                                {{ i.instructor.mnemonic }} - {{ i.instructor.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2" >
                            <label for="physicalRoom">Aula Física</label>
                            <select  class="form-control" id="physicalRoom" v-model="selectedPhysicalRoom" >
                                <option :value="null">Ninguna</option>
                                <option v-for="room in sortedPhysicalRooms"
                                                v-bind:key="room.id"
                                                :value="room.id"
                                                >
                                                {{ room.mnemonic }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3" >
                            <label for="virtualRoom">Aula Virtual</label>
                            <input type="text"  class="form-control" id="virtualRoom" v-model="selectedVirtualRoom" @click="onVirtualRoomClick()" readonly/>


                        </div>

                        <!-- <div class="col-md-3 form-group" >
                            <label for="supportPeople">Soporte</label>
                            <input type="text"  class="form-control" id="supportPeople"  @click="onSupportPeopleClick()" readonly />
                        </div> -->



                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-2"  >
                            <div class="col-md-6 mt-2" style="cursor: pointer" @click="onSupportPeopleClick()">
                            <strong > Equipo de Soporte  </strong>
                            </div>
                            <div v-html="selectedSupportStaffSring"> </div>
                        </div>
                    </div>

                </div>
            </div>
       </form>


        <modal name="addVirtualMeeting" height="auto"  width="70%" :clickToClose="false" :scrollable="true">
            <add-virtual-meeting
                :program-id="selectedProgram"
                :program-name="selectedProgramName"
                :booking-vml="selectedLink"

                @update-selected-vml="updateSelectedVirtualMeetingLink"
                @cancel-add-vml="cancelAddVirtualMeetingLink"

            />

        </modal>

        <modal name="addSupportPeople" height="auto"  width="70%" :clickToClose="false" :scrollable="true">
            <add-support-people
                :booking-staff="bookingSupportPeople"
                :program-name="selectedProgramName"

                @update-support-people="updateSelectedSupportPeople"
                @cancel-support-people="cancelAddSupportPeople"

            />

        </modal>
        <notifications group="notificationGroup" position="top center" />



    </div>

</template>

<script>

import areaApi from "../services/area";
import instructorAreasApi from "../services/instructorarea";
import programsApi from "../services/program";
import physicalRoomsApi from "../services/physicalroom";
import virtualRoomsApi from "../services/virtualroom";
import programVirtualMeetingLinksApi from "../services/programvirtualmeetinglink";
import supportPeopleApi from "../services/supportperson";
import bookingApi from "../services/booking";
import virtualMeetingLinkApi from "..//services/virtualmeetinglink";
import AddVirtualMeeting from './AddVirtualMeeting.vue';
import AddSupportPeople from './AddSupportPeople.vue';

import moment from "moment";
import { Remarkable } from 'remarkable'

const ROLE_COORD = 1;
const ROLE_ACAD = 2;
const ROLE_TI = 3;

const SUPPORT_TYPE_PHYSICAL = 0;
const SUPPORT_TYPE_VIRTUAL = 1;

export default {
  components: { AddVirtualMeeting, AddSupportPeople },

data() {
    return {

        programs: [],
        areas: [],
        instructorAreas: [],
        physicalrooms: [],

        bookingDate:null,
        startTime: null,
        endTime: null,
        selectedArea: null,
        selectedInstructor: null,
        selectedProgram: 0,
        selectedPhysicalRoom: null,

        selectedVirtualRoom: "",
        bookingSupportPeople: [],

        selectedSupportStaffSring: "",

        selectedLink: {},
        topic: "",

        virtualMeetingLink:{},

        virtualRoomCapacity:300,

        newBookingError:"",
        saving:false,

        booking:null,
        fetching: false,
    }
},

 props: {
        bookingId: {
            type: Array,
            default: [],
        },
 },

computed: {
    sortedPrograms() {
        return this.programs.sort((a, b) => a.mnemonic > b.mnemonic);
    },

    selectedProgramName(){
        return this.selectedProgram!=0 ? this.programs.filter( p => p.id == this.selectedProgram)[0].mnemonic : ""
    },


    sortedAreas() {
        return this.areas.sort((a, b) => a.mnemonic > b.mnemonic);
    },

    selectableInstructors() {
        return (this.selectedArea == 0
            ? this.instructorAreas
            : this.instructorAreas.filter(
                    (ia) => ia.area_id == this.selectedArea
                )
        ).sort((a, b) => a.instructor.mnemonic > b.instructor.mnemonic);
    },

    sortedPhysicalRooms() {
        return this.physicalrooms.sort((a, b) => a.mnemonic > b.mnemonic);
    },



},

    filters: {
            supportRole: function (value) {
                switch(value) {
                    case ROLE_COORD:
                        return 'Coordinación Académica'
                    case ROLE_ACAD:
                        return 'Soporte Académico'
                    case ROLE_TI:
                        return 'Soporte TI'
                }

            },

            supportType: function (value) {
                switch(value) {
                    case SUPPORT_TYPE_PHYSICAL:
                        return 'Físico'
                    case SUPPORT_TYPE_VIRTUAL:
                        return 'Virtual'

                }

            },

        },



    async mounted(){

        await this.fetchPrograms()
        await this.fetchAreas()
        await this.fetchInstructorAreas()
        await this.fetchPhysicalRooms()


        //check if wants to edit bookings
        if(this.bookingId.length>0) {

            await this.loadBookingInfo()
            console.log("Fetched Booking", this.booking)

        }



    },
    methods: {
        async loadBookingInfo(){


            if (this.bookingId.length == 1) //if it is editing only one booking
            {

                await this.fetchBooking(this.bookingId[0])

                this.bookingDate = moment(this.booking.booking_date).toDate().toISOString().substr(0,10)//Ex: '2022-03-11'
                this.startTime = moment(this.booking.start_time).toDate().format("HH:mm");
                this.endTime =  moment(this.booking.end_time).toDate().format("HH:mm");
                this.selectedProgram  = this.booking.program_id
                this.selectedArea = this.booking.area_id
                this.selectedInstructor = this.booking.instructor_id
                this.selectedPhysicalRoom = this.booking.physical_room_id
                this.selectedVirtualRoom = this.booking.virtual_meeting_link.virtual_room.name
                this.topic = this.booking.topic
                this.selectedSupportStaffSring = this.booking.support_people_string

                //Load Support People Array for Support People Modal
                console.log("This booking",this.booking.booking_support_persons)
                this.booking.booking_support_persons.forEach(b => {
                    this.bookingSupportPeople.push({
                        support_person_id: b.support_person_id,
                        role: b.support_role,
                        type: b.support_type,
                        name: b.support_person.mnemonic + ' - ' + b.support_person.name,
                        role_text: this.$options.filters.supportRole(b.support_role) ,
                        type_text: this.$options.filters.supportType(b.support_type) ,
                        })
                });




                //For Virtual Meeting Link Modal
                this.selectedLink.virtual_room_name = this.booking.virtual_meeting_link.virtual_room.name
                this.selectedLink.virtual_meeting_link_id = this.booking.virtual_meeting_link.id
                this.selectedLink.virtual_meeting_link = this.booking.virtual_meeting_link.link
                this.selectedLink.password = this.booking.virtual_meeting_link.password
                this.selectedLink.waiting_room = this.booking.virtual_meeting_link.waiting_room
                this.selectedLink.virtualRoomCapacity = this.booking.virtual_room_capacity


                //Check if loaded link is the default one for selected program
                if( this.selectedProgram > 0) {
                    var defaultLink  = await programVirtualMeetingLinksApi.getDefaultLink(this.selectedProgram)

                    if (this.selectedLink.virtual_meeting_link_id == defaultLink.virtual_meeting_link_id){
                        this.selectedLink.is_default_link = true
                    }
                    else {
                        this.selectedLink.is_default_link = false
                    }
                }

            }
            else if(this.bookingId.length>1) {
                console.log("Selected Link", this.selectedLink)
                this.loadMultipleBookings()
            }

        },

        async loadMultipleBookings (){
            //This functions iterates over all editing bookings and checks field by field if they have the same value so the form can be populated accordingly
            var multipleBookings = []


            multipleBookings = await bookingApi.getBunch({bookings_ids: this.bookingId}).bookings


            multipleBookings.forEach(booking => {
                //check if all fields are the same in all selected bookings

                //booking_date
                if (multipleBookings.filter(b =>moment( b.booking_date).toDate().toISOString().substr(0,10) == moment( booking.booking_date).toDate().toISOString().substr(0,10)).length == multipleBookings.length) {
                    console.log ("todas las fechas son iguales")
                    this.bookingDate = moment(booking.booking_date).toDate().toISOString().substr(0,10)//Ex: '2022-03-11'
                }

                //start_time
                if (multipleBookings.filter(b =>moment(b.start_time).toDate().format("HH:mm") == moment(booking.start_time).toDate().format("HH:mm")).length == multipleBookings.length) {
                    console.log ("todas las horas de inicio son iguales")
                    this.startTime = moment(booking.start_time).toDate().format("HH:mm");
                }

                //end_time
                if (multipleBookings.filter(b =>moment(b.end_time).toDate().format("HH:mm") == moment(booking.end_time).toDate().format("HH:mm")).length == multipleBookings.length) {
                    console.log ("todas las horas de fin son iguales")
                    this.endTime =  moment(booking.end_time).toDate().format("HH:mm");
                }

                //program_id
                if (multipleBookings.filter(b => b.program_id == booking.program_id).length == multipleBookings.length) {
                    console.log ("todas los programas son iguales")
                    this.selectedProgram = booking.program_id

                         //virtual_room
                        if (multipleBookings.filter(b => b.virtual_room_id == booking.virtual_room_id).length == multipleBookings.length) {
                            console.log ("todas las aulas virtuales son iguales")
                            this.selectedVirtualRoom = booking.virtual_room_name
                        }

                    //For Virtual Meeting Link Modal

                    if (multipleBookings.filter(b => b.link_id == booking.link_id).length == multipleBookings.length) {
                        this.selectedLink.virtual_meeting_link_id = booking.link.id
                        this.selectedLink.virtual_meeting_link = booking.link
                        this.selectedLink.password = booking.password
                        this.selectedLink.waiting_room = booking.waiting_room
                        this.selectedLink.virtual_room_name = booking.virtual_room_name

                    }

                }

                //area_id
                if (multipleBookings.filter(b => b.area_id == booking.area_id).length == multipleBookings.length) {
                    console.log ("todas las areas son iguales")
                    this.selectedArea = booking.area_id
                }

                //instructor_id
                if (multipleBookings.filter(b => b.instructor_id == booking.instructor_id).length == multipleBookings.length) {
                    console.log ("todas los instructores son iguales")
                    this.selectedInstructor = booking.instructor_id
                }

                //physical_room
                if (multipleBookings.filter(b => b.physical_room_id == booking.physical_room_id).length == multipleBookings.length) {
                    console.log ("todas las aulas físicas son iguales")
                    this.selectedPhysicalRoom = booking.physical_room_id

                }



                //topic
                if (multipleBookings.filter(b => b.meeting_topic == booking.meeting_topic).length == multipleBookings.length) {
                    console.log ("todas los temas de reunión son iguales")
                    this.topic = booking.meeting_topic

                }

                //Support String
                if (multipleBookings.filter(b => b.support == booking.support).length == multipleBookings.length) {
                    console.log ("todas los soportes son iguales")
                    this.selectedSupportStaffSring = booking.support

                }





                if (multipleBookings.filter(b => b.virtual_room_capacity == booking.virtual_room_capacity).length == multipleBookings.length) {
                    this.selectedLink.virtualRoomCapacity = booking.virtual_room_capacity
                }

            });


                //Check if loaded link is the default one for selected program
                if( this.selectedProgram > 0) {
                    var defaultLink  = await programVirtualMeetingLinksApi.getDefaultLink(this.selectedProgram)

                    if (this.selectedLink.virtual_meeting_link_id == defaultLink.virtual_meeting_link_id){
                        this.selectedLink.is_default_link = true
                    }
                    else {
                        this.selectedLink.is_default_link = false
                    }
                }

            // for (const b of this.bookingId) {
            //     const booking = await this.fetchBooking(b)
            //     multipleBookings.push(booking)
            //     console.log("Booking",booking);
            // }


            console.log("Multiple Bookings for Edition",multipleBookings)
        },

        async fetchBooking(id) {
            this.fetching = true
            this.booking = null
            try {
                this.booking = await bookingApi.get(id)
            } catch (e) {
                console.log(e)
            } finally {
                this.fetching = false
            }
        },


        closeAddBooking(){
            console.log("closing Add Booking")
            this.$emit('add-booking-close')
        },

        validateData() {


            if (this.bookingDate == null || !moment(this.bookingDate).isValid()){
                this.newBookingError = "Escoja una fecha para esta sesión"
                return false
            }


            if ( this.selectedProgram==0) {
                this.newBookingError = "Seleccione el programa al que pertenece esta sesión"
                return false
            }

            if ( this.startTime==null) {
                this.newBookingError = "Escriba la hora de inicio de esta sesión"
                return false
            }

            if ( this.endTime == null) {
                this.newBookingError = "Escriba la hora de fin de esta sesión"
                return false
            }

            else {
                this.newBookingError = ""
                return true
            }

        },

        async saveBooking(){


            this.saving = true;

            this.newBookingError = ""

            if (!this.validateData()){
                this.saving= false

                return
            }


            try {
                var bookingObj = {
                    booking_date: moment(this.bookingDate).toDate(),
                    program: this.selectedProgram,
                    topic: this.topic,

                    //When Formatting Time for using with HTML time input
                    startTime: moment(this.startTime,"hh:mm").toDate(),
                    endTime: moment(this.endTime,"hh:mm").toDate(),

                    area: this.selectedArea,
                    instructor: this.selectedInstructor,
                    physicalRoom: this.selectedPhysicalRoom,
                    virtualRoom: this.selectedLink.virtual_room_id,
                    supportPeople: this.bookingSupportPeople,
                    link: this.selectedLink.virtual_meeting_link_id ? this.selectedLink.virtual_meeting_link_id: null,
                    virtualRoomCapacity: this.selectedLink.virtualRoomCapacity ?
                                        this.selectedLink.virtualRoomCapacity :
                                        this.virtualRoomCapacity,
                };


                if (this.bookingId.length>0) { //Edit

                    for (var i=0; i< this.bookingId.length; i++) {
                        var responseData = await bookingApi.update( this.bookingId[i], {
                            newBooking: bookingObj,
                        });
                    }
                }
                else {
                    var responseData = await bookingApi.create({
                        newBooking: bookingObj,
                    });
                }

                this.$notify({
                    group: "notificationGroup",
                    type: "success",
                    title: "Registro guardado exitosamente.",
                });


                this.$emit('add-booking-save')


            } catch (e) {
                console.log("error",e)
                console.log("Hola")
                // console.log(e.response.data);
                // this.$notify({
                //     group: "notificationGroup",
                //     type: "error",
                //     title: "Error",
                //     text: e.response.data.errorMessage,
                // });
            } finally {

                this.saving = false;
            }
        },

        onVirtualRoomClick(){
            if (this.selectedProgram==0) {
                return
            }
            this.$modal.show("addVirtualMeeting")

        },
        onSupportPeopleClick (){

            this.$modal.show("addSupportPeople")

        },
        updateSelectedVirtualMeetingLink(vml){
            this.selectedLink = vml
            console.log("selectedLink", this.selectedLink)
            this.selectedVirtualRoom = vml.virtual_room_name
            this.$modal.hide("addVirtualMeeting")


        },
        cancelAddVirtualMeetingLink() {
            this.$modal.hide("addVirtualMeeting")

        },

        updateSelectedSupportPeople(sp,str){
            console.log("Support People in Booking", sp)
            this.bookingSupportPeople = sp

            this.selectedSupportStaffSring = str
            console.log("String",str)
            this.$modal.hide("addSupportPeople")

            var md = new Remarkable();

            this.selectedSupportStaffSring = md.render(str)




        },

        cancelAddSupportPeople() {
            this.$modal.hide("addSupportPeople")

        },

        async onProgramChange (){


            var defaultLink  = await programVirtualMeetingLinksApi.getDefaultLink(this.selectedProgram)

            if (defaultLink.error){
                this.selectedLink ={}
                this.selectedVirtualRoom = ""
                return
            }

            this.selectedLink = defaultLink
            this.selectedVirtualRoom = this.selectedLink.virtual_room_name


            this.$notify({
                            group: "notificationGroup",
                            type: "info",
                            title: "Se aplicó el link predeterminado para el programa seleccionado.",
                            text:   "Tenga en cuenta que este link podría no estar disponible " +
                                "en la fecha de la sesión que está registrando.",
                            duration: -1,
                            width: '50%'
                        });

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
                this.areas = await areaApi.getAll()
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
        async fetchInstructorAreas() {
            try {
                this.instructorAreas = await instructorAreasApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de profesores"
                });
            }
        },

        async fetchPhysicalRooms() {
            try {
                this.physicalrooms = await physicalRoomsApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de aulas"
                });
            }
        },




    }
}
</script>

<style scoped>


</style>

