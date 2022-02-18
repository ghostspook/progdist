<template>

   <div style="overflow: scroll;">
       <form>
            <div class="card">
                <h5 class="card-header">
                    <span>Nueva Sesión </span>
                    <span class="close-btn pull-right btn"  @click="closeNewSession()">X</span>

                    <!-- <button class="close-btn pull-right" @click="closeNewSession()">X</button> -->
                </h5>
                <div class="card-body">
                    <div class="row">

                        <div class="form-group col-md-2">
                              <label for="bookingDate">Fecha</label>
                            <input type="Date" id="bookingDate" class="form-control" v-model="bookingDate" />
                        </div>

                        <div class="col-md-2" >
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
                        <div class="col-md-2" >
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

                        <div class="col-md-2" >
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

                        <div class="col-md-2" >
                            <label for="virtualRoom">Aula Virtual</label>
                            <input type="text"  class="form-control" id="virtualRoom" v-model="selectedVirtualRoom" @click="onVirtualRoomClick()" readonly/>
                            
                                
                        </div>

                        <div class="col-md-3 form-group" >
                            <label for="supportPeople">Soporte</label>
                            <input type="text"  class="form-control" id="supportPeople" v-model="topic" />
                        </div>



                    </div>

                </div>
            </div>
       </form>


        <modal name="addVirtualMeeting" height="auto"  width="75%" :clickToClose="false">
            <add-virtual-meeting
                :program-id="selectedProgram"
                :program-name="selectedProgramName"
                :booking-vml="selectedLink"

                @update-selected-vml="updateSelectedVirtualMeetingLink"
                @cancel-add-vml="cancelAddVirtualMeetingLink"

            />

        </modal>


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

export default {
  components: { AddVirtualMeeting },

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

        selectedLink: {},
        topic: "",

        virtualMeetingLink:{},

        virtualRoomCapacity:300,


    }
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

    async mounted(){
        await this.fetchPrograms()
        await this.fetchAreas()
        await this.fetchInstructorAreas()
        await this.fetchPhysicalRooms()

    },
    methods: {
        closeNewSession(){
            console.log("closing Add Booking")
            this.$emit('add-booking-close')
        },

        onVirtualRoomClick(){
            if (this.selectedProgram==0) {
                return
            }
            this.$modal.show("addVirtualMeeting")

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

        async onProgramChange (){
            

            var defaultLink  = await programVirtualMeetingLinksApi.getDefaultLink(this.selectedProgram)

            if (defaultLink.error){
                this.selectedLink ={}
                this.selectedVirtualRoom = ""    
                return
            }

            this.selectedLink = defaultLink
            this.selectedVirtualRoom = this.selectedLink.virtual_room_name
            
            
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

<style>

</style>
