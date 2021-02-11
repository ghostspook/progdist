<template>
    <div class="ml-2 mr-2">
        <button :class="newButtonClass" @click="onNewClick">
            <i :class="newButtonIcon"></i>
            {{ newButtonText }}
        </button>
        <div v-if="displayForm" class="mt-2 mb-3">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="bookingDate">Fecha</label>
                                <datepicker
                                    id="bookingDate"
                                    v-model="bookingDate"
                                    :format="myFormatter"
                                    :language="es"
                                    :bootstrap-styling="true"
                                >
                                </datepicker>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="programs">Programa</label>
                                <v-select
                                    id="programs"
                                    :options="sortedPrograms"
                                   @input="onChangeProgram"
                                    label="mnemonic"
                                    v-model="selectedProgram"
                                    :reduce="(sp) => (!sp ? null : sp.id)"
                                />
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="topic">Tema</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="topic"
                                    v-model="topic"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="startTime">Inicia</label>
                                <timeselector
                                    id="startTime"
                                    v-model="startTime"
                                    displayFormat="H:mm"
                                    :interval="timeFormat"
                                ></timeselector>

                            </div>
                            <div class="col-md-3 form-group">
                                <label for="endTime">Termina</label>
                                <timeselector
                                    id="endTime"
                                    v-model="endTime"
                                    displayFormat="H:mm"
                                    :interval="timeFormat"
                                ></timeselector>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="areas">Área</label>
                                <select
                                    class="form-control"
                                    id="areas"
                                    v-model="selectedArea"
                                >
                                    <option :value="null">Ninguna</option>
                                    <option
                                        v-for="a in sortedAreas"
                                        v-bind:key="a.id"
                                        :value="a.id"
                                    >
                                        {{ a.mnemonic }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="instructors">Instructor</label>
                                <select
                                    class="form-control"
                                    id="instructors"
                                    v-model="selectedInstructor"
                                >
                                    <option :value="null">Ninguno</option>
                                    <option
                                        v-for="i in selectableInstructors"
                                        v-bind:key="i.id"
                                        :value="i.instructor.id"
                                    >
                                        {{ i.instructor.mnemonic }} -
                                        {{ i.instructor.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="physicalRoom">Aula Física</label>
                                <select
                                    class="form-control"
                                    id="physicalroom"
                                    v-model="selectedPhysicalRoom"
                                >
                                    <option :value="null">Ninguna</option>
                                    <option
                                        v-for="room in sortedPhysicalRooms"
                                        v-bind:key="room.id"
                                        :value="room.id"
                                    >
                                        {{ room.mnemonic }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="virtualMeetingLink">Link</label>
                                <select
                                    class="form-control"
                                    id="virtualMeetingLink"
                                    v-model="selectedLink"

                                >
                                    <option :value="null">Ninguno</option>
                                    <option
                                        v-for="vml in virtualmeetinglinks"
                                        v-bind:key="vml.virtual_meeting_link_id"
                                        :value="vml.virtual_meeting_link_id"
                                    >

                                        {{vml.virtual_meeting_link.link}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="supportPeople">Soportes</label>
                                <multiselect
                                    id="supportPeople"
                                    v-model="selectedSupportPeople"
                                    :options="selectableSupportPeople"
                                    track-by="label"
                                    label="label"
                                    :multiple="true"
                                    :taggable="true"
                                    :showLabels="true"
                                    :hide-selected="true"
                                ></multiselect>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <button
                        :disabled="saving"
                        class="btn btn-success"
                        @click="onSaveClick"
                    >
                        Guardar
                    </button>
                </div>
            </div>
        </div>
        <notifications group="notificationGroup" position="top center" />
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import Timeselector from "vue-timeselector";
import moment from "moment";
import { en, es } from "vuejs-datepicker/dist/locale";
import areaApi from "../services/area";
import instructorAreasApi from "../services/instructorarea";
import programsApi from "../services/program";
import physicalRoomsApi from "../services/physicalroom";
import virtualRoomsApi from "../services/virtualroom";
import programVirtualMeetingLinksApi from "../services/programvirtualmeetinglink";
import supportPeopleApi from "../services/supportperson";
import bookingApi from "../services/booking";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

const ROLE_COORD = 1;
const ROLE_ACAD = 2;
const ROLE_TI = 3;

const SUPPORT_TYPE_PHYSICAL = 0;
const SUPPORT_TYPE_VIRTUAL = 1;

export default {
    components: {
        Datepicker,
        Timeselector,
        vSelect,
        Multiselect,
    },
    data() {
        return {
            displayForm: false,
            en: en,
            es: es,
            areas: [],
            bookingDate: null,
            startTime: null,
            endTime: null,
            selectedArea: null,
            selectedInstructor: null,
            selectedProgram: null,
            selectedPhysicalRoom: null,
            selectedVirtualRoom: null,
            selectedLink: null,
            instructorAreas: [],
            programs: [],
            physicalrooms: [],
            supportpeople: [],
            virtualrooms: [],
            virtualmeetinglinks: [],
            defaultvirtualmeetinglink: null,
            links: [], // no me acuerdo para qué se usaba, tal vez hay que elimarla.
            timeFormat: { h: 1, m: 5, s: 10 },
            selectedSupportPeople: [], //for MultiSelect
            topic: "",
            saving: false,
            error: null,
            isDirty: false,
            dirtyBooking: 0,
        };
    },
    computed: {
        newButtonText() {
            return this.displayForm ? "Esconder" : "Nuevo";
        },
        newButtonIcon() {
            return this.displayForm ? "fa fa-minus" : "fa fa-plus";
        },

        sortedAreas() {
            return this.areas.sort((a, b) => a.mnemonic > b.mnemonic);
        },
        sortedPrograms() {
            return this.programs.sort((a, b) => a.mnemonic > b.mnemonic);
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
            console.log(this.physicalrooms)
            return this.physicalrooms.sort((a, b) => a.mnemonic > b.mnemonic);
        },

        sortedVirtualRooms() {
            return this.virtualrooms.sort((a, b) => a.mnemonic > b.mnemonic);
        },


        selectableSupportPeople() {
            var returnList = [];
            this.supportpeople.forEach((person) => {
                returnList.push({
                    support_person_id: person.id,
                    role: ROLE_COORD,
                    type: SUPPORT_TYPE_PHYSICAL,
                    label: "Coord - " + person.mnemonic + " - Físico",
                });
                returnList.push({
                    support_person_id: person.id,
                    role: ROLE_COORD,
                    type: SUPPORT_TYPE_VIRTUAL,
                    label: "Coord - " + person.mnemonic + " - Virtual",
                });
                returnList.push({
                    support_person_id: person.id,
                    role: ROLE_ACAD,
                    type: SUPPORT_TYPE_PHYSICAL,
                    label: "Acad - " + person.mnemonic + " - Físico",
                });
                returnList.push({
                    support_person_id: person.id,
                    role: ROLE_ACAD,
                    type: SUPPORT_TYPE_VIRTUAL,
                    label: "Acad - " + person.mnemonic + " - Virtual",
                });
                returnList.push({
                    support_person_id: person.id,
                    role: ROLE_TI,
                    type: SUPPORT_TYPE_PHYSICAL,
                    label: "TI - " + person.mnemonic + " - Físico",
                });
                returnList.push({
                    support_person_id: person.id,
                    role: ROLE_TI,
                    type: SUPPORT_TYPE_VIRTUAL,
                    label: "TI - " + person.mnemonic + " - Virtual",
                });
            });
            return returnList;
        },

        newButtonClass() {
            return this.displayForm ? "btn btn-default mt-2 mb-3 " : "btn btn-success mt-2 mb-3";
        },

        timeListing() {
            var initialTime = 7; // 7h
            var finalTime = 23; // 23h
            var minutesInterval = 5;
            var timeList = [];
            timeList.push({
                id: initialTime + ":" + "00",
                hour: initialTime + ":" + "00",
            });
            var nextMinutes = minutesInterval;
            var minutePrecedingZero = "";
            for (var i = initialTime; i <= finalTime; i++) {
                while (nextMinutes < 60) {
                    minutePrecedingZero = nextMinutes < 10 ? "0" : "";

                    timeList.push({
                        id: i + ":" + minutePrecedingZero + nextMinutes,
                        hour: i + ":" + minutePrecedingZero + nextMinutes,
                    });
                    nextMinutes = nextMinutes + minutesInterval;
                }
                nextMinutes = 0;
            }
            return timeList;
        },
    },
    async mounted() {
        await this.fetchAreas()
        await this.fetchInstructorAreas()
        await this.fetchPrograms()
        await this.fetchPhysicalRooms()
        await this.fetchVirtualRooms()
        await this.fetchSupportPeople()
    },
    methods: {
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
        async fetchSupportPeople() {
            try {
                this.supportpeople = await supportPeopleApi.getAll();
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
        resetData() {
            this.bookingDate = null;
            this.startTime = null;
            this.endTime = null;
            this.topic = "";
            this.selectedProgram = null;
            this.selectedArea = null;
            this.selectedInstructor = null;
            this.selectedPhysicalRoom = null;
            this.selectedVirtualRoom = null;
            this.selectedLink = null;
            this.selectedSupportPeople = [];
        },
        onNewClick() {
            this.resetData();
            this.isDirty = false;
            this.displayForm = !this.displayForm;
        },
        myFormatter(date) {
            moment.locale("es");
            return moment(date).format("DD-MMM-yyyy");
        },

        async onChangeProgram (){

            await this.fetchLinkList()
            //find the default virtual meeting link ID for the selected program
            var self = this

            var defaultLink  = self.defaultvirtualmeetinglink = self.virtualmeetinglinks.filter(
                    (link) =>
                        link.virtual_meeting_link_id == link.program.default_virtual_meeting_link_id

                )[0].program.default_virtual_meeting_link_id;

            this.selectedLink = defaultLink

         //   console.log(this.virtualmeetinglinks)

            console.log("hola")
            console.log(this.selectedLink)

                this.$notify({
                    group: "notificationGroup",
                    type: "info",
                    title: "Se aplicó el link predeterminado para el programa seleccionado.",
                    text:   "Tenga en cuenta que este link podría no estar disponible " +
                           "en la fecha de la sesión que está registrando.",
                    duration: -1,
                    width: '50%'
                });


           return this.virtualmeetinglinks


        },

        async fetchLinkList() {
           this.virtualmeetinglinks = await programVirtualMeetingLinksApi.get(this.selectedProgram)
        },

        async onEdit(id) {
            this.resetData();
            this.displayForm = true;
            this.isDirty = true;
            this.dirtyBooking = id;

            console.log(id);
            var booking = await bookingApi.get(this.dirtyBooking);

            this.bookingDate = moment(
                booking.booking_date,
                "YYYY-MM-DD"
            ).toDate();


            this.selectedProgram = booking.program.id;
            await this.fetchLinkList()
            this.topic = booking.topic;

            this.startTime = moment(booking.start_time).toDate();
            this.endTime = moment(booking.end_time).toDate();

            this.selectedArea = booking.area_id;
            this.selectedInstructor = booking.instructor_id;

            this.selectedLink = booking.virtual_meeting_link_id
            this.selectedPhysicalRoom = booking.physical_room_id;

            var self = this;

            booking.booking_support_persons.forEach(function (bsp) {

                var selectedItems = self.selectableSupportPeople.filter(
                    (selSp) =>
                        selSp.support_person_id == bsp.support_person_id &&
                        selSp.role == bsp.support_role &&
                        selSp.type == bsp.support_type
                );
                self.selectedSupportPeople.push(selectedItems[0]);
            });
        },

        async onSaveClick() {
            this.saving = true;
            try {
                var bookingObj = {
                    booking_date: this.bookingDate,
                    program: this.selectedProgram,
                    topic: this.topic,
                    startTime: this.startTime,
                    endTime: this.endTime,
                    area: this.selectedArea,
                    instructor: this.selectedInstructor,
                    physicalRoom: this.selectedPhysicalRoom,
                    virtualRoom: this.selectedVirtualRoom,
                    supportPeople: this.selectedSupportPeople,
                    link: this.selectedLink
                };
                if (!this.isDirty) {
                    var responseData = await bookingApi.create({
                        newBooking: bookingObj,
                    });
                } else {
                    var responseData = await bookingApi.update(
                        this.dirtyBooking,
                        {
                            newBooking: bookingObj,
                        }
                    );
                }

                this.$notify({
                    group: "notificationGroup",
                    type: "success",
                    title: "Registro guardado exitosamente.",
                });
                setTimeout(2000);
                location.reload();
            } catch (e) {
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
    },
};
</script>
