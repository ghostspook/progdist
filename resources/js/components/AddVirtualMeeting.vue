<template>
    <div>
        <div class="card">
            <div class="card-header p-3 mb-2 bg-primary text-white">
                <div class="row">
                    <div class="col-md-11">
                        <h4>Aula Virtual seleccionada para esta sesión de {{ programName }}</h4>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success" @click="saveLink()">OK</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
               <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Aula Virtual</th>
                            <th scope="col">Link</th>
                            <th scope="col">Contraseña</th>
                            <th scope="col">Sala de Espera</th>
                            <th scope="col">Default</th>
                            <th scope="col">Capacidad</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ selectedLink ? selectedLink.virtual_room_name : 'ninguna' }}</td>
                            <td>{{ selectedLink ? selectedLink.virtual_meeting_link : 'ninguno' }}</td>
                            <td>{{ selectedLink ? selectedLink.password : '' }}</td>
                            <td>{{ selectedLink ? selectedLink.waiting_room ? 'Sí' : 'No' : '' }} </td>
                             <td>
                                {{ selectedLink ? selectedLink.is_default_link ? 'Sí' : 'No':'' }}
                            </td>
                            <td>
                                <select
                                    class="form-control"
                                    id="virtualRoomCapacity"
                                    v-model="selectedLink.virtualRoomCapacity"

                                >
                                    <option > 300 </option>
                                    <option > 500 </option>
                                    <option > 1000 </option>
                                </select>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header p-3 mb-2 bg-secondary text-white">
                            <h5>Escoger otra Aula Virtual</h5>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">Aula Virtual</th>
                                <th scope="col">Link</th>
                                <th scope="col">Default</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(link,index) in virtualMeetinglinks" :key="index">
                                    <td>
                                        <span class="btn"  @click="selectVirtualMeetingLink(link.virtual_meeting_link_id)"> {{link.virtual_room_name}} </span>
                                    </td>
                                    <td>
                                        <span class="btn"  @click="selectVirtualMeetingLink(link.virtual_meeting_link_id)"> {{ link.virtual_meeting_link}} </span>
                                    </td>
                                    <td>
                                        <span class="btn"  @click="makeDefaultVirtualMeetingLink(link.virtual_meeting_link_id)"> {{ link.is_default_link? 'Sí' : 'No'}} </span>

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card-header p-3 mb-2 bg-secondary text-white">
                            <h5>Crear un nuevo Link</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label><strong>Aula Virtual</strong></label>
                                </div>
                                <div class="col-md-2">
                                   <select
                                    class="form-control"
                                    id="virtualRoom"
                                    v-model="selectedVirtualRoom"
                                    >
                                            <option :value="null">Ninguna</option>
                                            <option
                                                    v-for="vroom in sortedVirtualRooms"
                                                    v-bind:key="vroom.id"
                                                    :value="vroom.id"
                                                >
                                                    {{ vroom.mnemonic }}
                                            </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label><strong>Link</strong></label>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <input type="link" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-3">
                                    <label><strong>Password</strong></label>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <input type="text" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mt-3">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="flexSwitchCheckDefault"><strong>Sala de Espera</strong></label>
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-3">
                                    <label><strong>Capacidad</strong></label>
                                </div>
                                <div class="col-md-8 mt-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"  id="inlineRadio1" value="300">
                                        <label class="form-check-label" for="inlineRadio1">300</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"  id="inlineRadio2" value="500" >
                                        <label class="form-check-label" for="inlineRadio2">500</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"  id="inlineRadio3" value="1000" >
                                        <label class="form-check-label" for="inlineRadio3">1000</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary"> Crear Link</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import virtualRoomsApi from "../services/virtualroom";

import programVirtualMeetingLinksApi from "../services/programvirtualmeetinglink";


export default {
    props: {
        programId: {
            type: Number,
            required: true
        },
        programName: {
            type: String,
            required: true,
        },
        bookingVml: { //Booking Virtual Meeting Link
            type: Object,
            default: () => ({}),
        },


    },
    data() {
        return{
            virtualrooms: [],
            virtualMeetinglinks: [],

            selectedVirtualRoom: null,

            selectedLink: {},


        }
    },

    computed: {
        sortedVirtualRooms() {
            return this.virtualrooms.sort((a, b) => a.mnemonic > b.mnemonic);
        },



    },


    async mounted() {
        await this.fetchVirtualRooms()
        await this.fetchVirtualMeetingLinks()

        if( this.bookingVml){
            this.selectedLink.virtualRoomCapacity = this.bookingVml.virtualRoomCapacity
            this.selectedLink = this.bookingVml
        }


    },
    methods: {
        saveLink(){
            this.$emit('update-selected-vml', this.selectedLink)
        },
        selectVirtualMeetingLink(linkId){
            this.selectedLink = this.virtualMeetinglinks.filter( vrl => vrl.virtual_meeting_link_id == linkId)[0]
            this.selectedLink.virtualRoomCapacity = "300"


            console.log("selected link", this.selectedLink)

        },

        async makeDefaultVirtualMeetingLink(linkId){

           await programVirtualMeetingLinksApi.setDefaultLink(linkId)
           await this.fetchVirtualMeetingLinks()


        },



        async fetchVirtualRooms() {
            try {
                this.virtualrooms = await virtualRoomsApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de aulas virtuales"
                });
            }
        },

        async fetchVirtualMeetingLinks() {
           this.virtualMeetinglinks = await programVirtualMeetingLinksApi.get(this.programId)
        },
    }
}
</script>
