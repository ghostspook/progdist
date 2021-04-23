<template>
<div class="card">
    <h5 class="card-header">
        Añadir link
    </h5>
    <div class="card-body">
        <div class="form-group">
            <label for="virtualRoom">Aula Virtual</label>
                <v-select
                    :options="virtualrooms"
                    label="mnemonic"
                    v-model="selectedVirtualRoom"

            />
        </div>
            <div class="form-group">
                <label for="newLink">URL</label>
                <input v-model="newLink" class="form-control" name="newLink" id="newLink" type="url" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input v-model="password" class="form-control" name="password" id="text" />
            </div>
            <div class="form-group">
                <label for="waiting_room">Sala de Espera</label>
                <select v-model="hasWaitingRoom" name="waiting_room" id="waiting_room" class="form-control">
                    <option value="0">No</option>
                    <option value="1" selected>Sí</option>
                </select>
            </div>
            <div class="col-md-12">
            <button
                :disabled="addingLink"
                class="btn btn-success"
                @click="onAddMeetingLinkClick"
            >
                Añadir Link
            </button>

                </div>

    </div>
    </div>
</template>

<script>

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import virtualMeetingLinkApi from "..//services/virtualmeetinglink";


export default {
    components: {
        vSelect,

    },
     props: {
        program: {
            type: Number,
            required: true
        },
        virtualrooms: {
            type: Array,
            default: []
        },
    },
    data() {
        return {
            selectedVirtualRoom: {},
            addingLink: false,
            isMeeting: false,
            newLink: null,
            password: null,
            hasWaitingRoom: 0,

        };
    },

    methods: {
          async onAddMeetingLinkClick() {

            this.addingLink = true;

            var linkObj = {
                        program_id: this.program, // (REUNIÓN) assumed
                        virtual_room_id: this.selectedVirtualRoom.id,
                        link: this.newLink,
                        password: this.password,
                        waiting_room: this.hasWaitingRoom
                    };
            try{
                var responseData = await virtualMeetingLinkApi.create({
                                    newVirtualMeetingLink: linkObj
                                })
                //    this.virtualmeetinglinks = []
                  //  this.virtualmeetinglinks.push ( { virtual_meeting_link_id: responseData['virtual_meeting_link_id'],
                                        // virtual_meeting_link: responseData['link']
                                        // })
                    //this.selectedLink = responseData['virtual_meeting_link_id']

            } catch (e){
                console.log(e.response.data);
                    this.$notify({
                        group: "notificationGroup",
                        type: "error",
                        title: "Error",
                        text: e.response.data.errorMessage,
                    });
            } finally {
                this.addingLink = false
              //  await this.onChangeVirtualMeetingLink()

            }
            this.$emit('on-add-link')

        }


    }

}
</script>
