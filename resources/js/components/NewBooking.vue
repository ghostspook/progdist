<template>
    <div class="ml-2 mr-2">
        <button class="btn btn-primary" @click="onNewClick">{{ newButtonText }}</button>
        <div v-if="displayForm" class="mt-2 mb-3">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="bookingDate">Fecha</label>
                                <datepicker id="bookingDate" :format="myFormatter" :language="es" :bootstrap-styling="true"  > </datepicker>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="programs">Programa</label>
                                <v-select id="programs" :options="sortedPrograms" label="mnemonic" v-model="selectedProgram" :reduce="sp => sp.id"/>
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="topic">Tema</label>
                                <input class="form-control" type="text" id="topic" v-model="topic">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="startTime">Inicia</label>
                                <timeselector id="startTime"  displayFormat="H:mm" :interval="timeFormat" ></timeselector>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="endTime">Termina</label>
                                <timeselector id="endTime" displayFormat="H:mm" :interval="timeFormat" ></timeselector>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="areas">Área</label>
                                <select class="form-control" id="areas" v-model="selectedArea">
                                    <option value="0">Ninguna</option>
                                    <option v-for="a in sortedAreas" v-bind:key="a.id" :value="a.id">{{ a.mnemonic }}</option>
                                </select>
                            </div>
                            <div class="col-md-5 form-group">
                                <label for="instructors">Instructor</label>
                                <select class="form-control" id="instructors" v-model="selectedInstructor">
                                    <option value="0">Ninguno</option>
                                    <option v-for="i in selectableInstructors" v-bind:key="i.id" :value="i.instructor.id">{{ i.instructor.mnemonic }} - {{ i.instructor.name }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="physicalRoom">Aula Física</label>
                                <select class="form-control" id="physicalroom" v-model="selectedPhysicalRoom">
                                    <option value="0">Ninguna</option>
                                    <option v-for="room in sortedPhysicalRooms" v-bind:key="room.id" :value="room.id">{{ room.mnemonic }} </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="virtualRoom">Aula Virtual</label>
                                <select class="form-control" id="virtualroom" v-model="selectedVirtualRoom">
                                    <option value="0">Ninguna</option>
                                    <option v-for="vroom in sortedVirtualRooms" v-bind:key="vroom.id" :value="vroom.id">{{ vroom.mnemonic }} </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="supportPeople">Soportes</label>
                                <multiselect id="supportPeople" v-model="selectedSupportPeople" :options="selectableSupportPeople" track-by="label" label="label" :multiple="true" :taggable="true" :showLabels="true" :hide-selected="true"></multiselect>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import Timeselector from 'vue-timeselector';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale';
import areaApi from '../services/area'
import instructorAreasApi from '../services/instructorarea'
import programsApi from '../services/program'
import physicalRoomsApi from '../services/physicalroom'
import virtualRoomsApi from '../services/virtualroom'
import supportPeopleApi from '../services/supportperson'
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"
import Multiselect from 'vue-multiselect'
import "vue-multiselect/dist/vue-multiselect.min.css"

const ROLE_COORD = 1
const ROLE_ACAD = 2
const ROLE_TI = 3

const SUPPORT_TYPE_PHYSICAL = 1
const SUPPORT_TYPE_VIRTUAL = 2


export default {
    components: {
        Datepicker,
        Timeselector,
        vSelect,
        Multiselect
    },
    data() {
        return {
            displayForm: false,
            en: en,
            es: es,
            areas: [],
            selectedArea: 0,
            selectedInstructor: 0,
            selectedProgram: 0,
            selectedPhysicalRoom: 0,
            selectedVirtualRoom: 0,
            instructorAreas: [],
            programs: [],
            physicalrooms: [],
            supportpeople: [],
            virtualrooms: [],
            timeFormat:{h:1, m:5, s:10},
            selectedSupportPeople: null, //for MultiSelect
            topic: ""
        }
    },
    computed: {
        newButtonText() {
            return this.displayForm ? "Esconder" : "Nuevo"
        },
        sortedAreas() {
            return this.areas.sort((a, b) => (a.mnemonic > b.mnemonic))
        },
        sortedPrograms() {
            return this.programs.sort((a, b) => (a.mnemonic > b.mnemonic))
        },
        selectableInstructors() {
            return (this.selectedArea == 0 ?
                this.instructorAreas
                : this.instructorAreas.filter(ia => ia.area_id == this.selectedArea))
                .sort((a, b) => a.instructor.mnemonic > b.instructor.mnemonic)
        },

        sortedPhysicalRooms() {
            return this.physicalrooms.sort((a, b) => (a.mnemonic > b.mnemonic))
        },

        sortedVirtualRooms() {

           return this.virtualrooms.sort((a, b) => (a.mnemonic > b.mnemonic))
        },

        selectableSupportPeople() {
            var returnList = []
            this.supportpeople.forEach(( person )=> {
                returnList.push({support_person_id: person.id, role: ROLE_COORD, type: SUPPORT_TYPE_PHYSICAL, label: "Coord - " + person.mnemonic +" - Físico"})
                returnList.push({support_person_id: person.id, role: ROLE_COORD, type: SUPPORT_TYPE_VIRTUAL, label: "Coord - " + person.mnemonic +" - Virtual"})
                returnList.push({support_person_id: person.id, role: ROLE_ACAD, type: SUPPORT_TYPE_PHYSICAL, label: "Acad - " + person.mnemonic +" - Físico"})
                returnList.push({support_person_id: person.id, role: ROLE_ACAD, type: SUPPORT_TYPE_VIRTUAL, label: "Acad - " + person.mnemonic +" - Virtual"})
                returnList.push({support_person_id: person.id, role: ROLE_TI, type: SUPPORT_TYPE_PHYSICAL, label: "TI - " + person.mnemonic +" - Físico"})
                returnList.push({support_person_id: person.id, role: ROLE_TI, type: SUPPORT_TYPE_VIRTUAL, label: "TI - " + person.mnemonic +" - Virtual"})
            })
            return returnList;
        }
    },
    async mounted() {
        this.areas = await areaApi.getAll()
        this.instructorAreas = await instructorAreasApi.getAll()
        this.programs = await programsApi.getAll()
        this.physicalrooms = await physicalRoomsApi.getAll()
        this.virtualrooms = await virtualRoomsApi.getAll()
        this.supportpeople = await supportPeopleApi.getAll()


    },
    methods: {
        onNewClick() {
            this.displayForm = !this.displayForm
        },
        myFormatter(date) {
            moment.locale('es');
            return moment(date).format('DD-MMM-yyyy');
        }


    }
}
</script>
