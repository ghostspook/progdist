<template>
    <div>
        <div class="card">
            <div class="card-header p-3 mb-2 bg-dark text-white">
                <div class="row">
                    <div class="col-md-9 float-left">
                        <h5>Aula Virtual seleccionada para esta sesión de {{ programName }}</h5>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button class="bg-dark text-white btn btn-danger mb-1" @click="cancelLink()">Cancelar</button>
                    </div>
                    <div class="col-md-1 ml-1 pull-right">
                        <button class="btn btn-success" @click="saveLink()">Guardar</button>
                    </div>
                </div>
                <table class="table bg-dark text-white mb-2">
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
                                <span class="btn bg-dark text-white"  @click="makeDefaultVirtualMeetingLink(selectedLink.virtual_meeting_link_id)">
                                    {{ isDefaultLink ? 'Sí' : 'No' }}
                                </span>

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
            </div>
            <div class="card-body mb-2">
                <div class="row">
                    <div v-if="!isMeeting" class="col-md-6">
                        <div class="card-header p-3 mb-2 bg-secondary text-white">
                            <h5>Escoger otra Aula Virtual</h5>
                        </div>
                        <!-- <select autocomplete="on" class="form-control col-md-12"  v-model="anotherVirtualMeetingLink" >
                                <option :value="null">Ninguna</option>
                                <option v-for="(link,index) in virtualMeetinglinks"
                                                :key="index"
                                                :value="link.virtual_meeting_link_id"
                                                >
                                                {{ link.virtual_room_name }} - {{ link.virtual_meeting_link }}
                                </option>
                        </select> -->

                        <v-select
                            :options="virtualMeetingLinks"
                            label="label"
                            v-model="anotherVirtualMeetingLink"
                            @input="onChangeVirtualMeetingLink"
                            :reduce="(vml) => (!vml ? null : vml.virtual_meeting_link_id)"

                        />

                    </div>


                    <div class="col-md-6">
                        <div class="card-header p-3 mb-2 bg-secondary text-white">
                            <h5>Crear un nuevo Link</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <span :class="newLinkError? 'alert alert-danger' :''">  {{ newLinkError }}</span>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label><strong>Aula Virtual</strong></label>
                                </div>
                                <div class="col-md-4">
                                   <select
                                    class="form-control"
                                    id="virtualRoom"
                                    v-model="newVirtualRoom"
                                    >
                                            <option :value="null">Ninguna</option>
                                            <option
                                                    v-for="vroom in sortedVirtualRooms"
                                                    v-bind:key="vroom.id"
                                                    :value="vroom"
                                                >
                                                    {{ vroom.mnemonic }}
                                            </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label><strong>Link</strong></label>
                                </div>
                                <div class="col-md-2 mt-3">
                                    <input type="url" size="35" v-model="newLink" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label><strong>Password</strong></label>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <input type="text" size="10" v-model="newPassword"/>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <label class="form-check-label" for="flexSwitchCheckDefault"><strong>Sala de Espera</strong></label>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" v-model="newWaitingRoom">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-4 d-flex justify-content-center">
                                    <button v-if="!addingLink" class="btn btn-primary" @click="onClickNewLink()"> Crear Link</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <notifications group="notificationGroup" position="top center" />

    </div>
</template>

<script>
import virtualRoomsApi from "../services/virtualroom";

import programVirtualMeetingLinksApi from "../services/programvirtualmeetinglink";
import virtualMeetingLinkApi from "../services/virtualmeetinglink"

import vSelect from "vue-select";

export default {

    components: {
        vSelect,
    },
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
            virtualMeetingLinks: [],

            selectedVirtualRoom: null,

            selectedLink: {},

            anotherVirtualMeetingLink: 0,

            addingLink: false,
            newVirtualRoom: null,
            newLink: "",
            newPassword: "",
            newWaitingRoom: false,

            newLinkError: "",
        }
    },

    computed: {
        sortedVirtualRooms() {
            return this.virtualrooms.sort((a, b) => a.mnemonic > b.mnemonic);
        },
        isDefaultLink(){
            console.log("Computing isDefault Link", this.selectedLink)
            return this.selectedLink.is_default_link ? true :  false
        },

        isMeeting(){
            return this.programId== 1 && this.programName=="(REUNIÓN)" ? true: false
        }


    },


    async mounted() {
        await this.fetchVirtualRooms()
        await this.fetchVirtualMeetingLinks()

        if( this.bookingVml){
            this.selectedLink = this.bookingVml

            if (typeof this.bookingVml.virtualRoomCapacity === 'undefined' ) {
                this.selectedLink.virtualRoomCapacity ="300"

            }
            else {
                this.selectedLink.virtualRoomCapacity = this.bookingVml.virtualRoomCapacity
            }

            console.log("selectedLink", this.selectedLink)

        }


    },
    methods: {
        saveLink(){
            this.$emit('update-selected-vml', this.selectedLink)
        },
        cancelLink(){
            this.$emit('cancel-add-vml')
        },
        onChangeVirtualMeetingLink(linkId){

            console.log("Selected Link Id", linkId)
            this.selectedLink = this.virtualMeetingLinks.filter( vml => vml.virtual_meeting_link_id == linkId)[0]
            this.selectedLink.virtualRoomCapacity = "300"


            console.log("selected link", this.selectedLink)

        },

        async makeDefaultVirtualMeetingLink(linkId){
            await programVirtualMeetingLinksApi.setDefaultLink(linkId)
            this.selectedLink.is_default_link = linkId
            await this.fetchVirtualMeetingLinks()

        },

        async onClickNewLink(){
            this.addingLink = true;
            this.newLinkError = ""
            console.log("Virtual Room", this.newVirtualRoom)

            if (this.newVirtualRoom == null){
                this.newLinkError = "Debe escoger el aula virtual"
                this.addingLink = false
                return
            }

            if (this.newLink == ""){
                this.newLinkError = "Debe escribir un link"
                this.addingLink = false
                return
            }
            if (this.newPassword=="" && !this.newWaitingRoom){
                this.newLinkError = "Debe escribir un password o habilitar la sala de espera"
                this.addingLink = false
                return
            }


            var linkObj = {
                        program_id: this.programId,
                        virtual_room_id: this.newVirtualRoom.id,
                        link: this.newLink,
                        password: this.newPassword,
                        waiting_room: this.newWaitingRoom==null ? false: this.newWaitingRoom ,
                    };
            try{
                var responseData = await virtualMeetingLinkApi.create({
                                    newVirtualMeetingLink: linkObj
                                })



                this.selectedLink.virtual_meeting_link = this.newLink
                this.selectedLink.virtual_meeting_link_id = responseData.virtual_meeting_link_id
                this.selectedLink.virtual_room_id = this.newVirtualRoom.id
                this.selectedLink.virtual_room_name = this.newVirtualRoom.name
                this.selectedLink.waiting_room = this.newWaitingRoom
                this.selectedLink.is_default_link = false

                this.newVirtualRoom = null
                this.newLink = ""
                this.newPassword =""
                this.newWaitingRoom = false



            } catch (e){
                console.log(e.response.data);
                this.newLinkError = e.response.data.message
                    this.$notify({
                        group: "notificationGroup",
                        type: "error",
                        title: "Error",
                        text: e.response.data.errorMessage,
                    });
            } finally {
                this.addingLink = false



            }

            this.fetchVirtualMeetingLinks()
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
           this.virtualMeetingLinks = await programVirtualMeetingLinksApi.get(this.programId)
           this.virtualMeetingLinks.forEach(vml => {
                vml.label = vml.virtual_meeting_link + '  @  ' + vml.virtual_room_name +  (vml.is_default_link ? '      Predeterminado' : '')

            });

        },
    }
}
</script>

<style scoped>

</style>
