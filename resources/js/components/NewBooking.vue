<template>
    <div class="ml-2 mr-2">
        <button class="btn btn-primary" @click="onNewClick">{{ newButtonText }}</button>
        <div v-if="displayForm" class="mt-2 mb-3">
            <form>
                <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="subject">Fecha</label>
                            <datepicker :format="myFormatter" :language="es" > </datepicker>
                        </div>
                        <div class="col-md-2">
                            <label for="areas">Área</label>
                            <select class="form-control" id="areas" v-model="selectedArea">
                                <option value="0">Ninguna</option>
                                <option v-for="a in sortedAreas" v-bind:key="a.id" :value="a.id">{{ a.mnemonic }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="instructors">Instructor</label>
                            <select class="form-control" id="instructors" v-model="selectedInstructor">
                                <option value="0">Ninguno</option>
                                <option v-for="i in selectableInstructors" v-bind:key="i.id" :value="i.instructor.id">{{ i.instructor.mnemonic }} - {{ i.instructor.name }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="programs">Programa</label>
                            <select class="form-control" id="programs" v-model="selectedProgram">
                                <option value="0">Ninguno</option>
                                <option v-for="p in sortedPrograms" v-bind:key="p.id" :value="p.id">{{ p.mnemonic }} </option>
                            </select>

                        </div>
                        <div class="col-md-2">
                            <label for="subject">Inicia</label>
                            <timeselector  displayFormat="H:mm" :interval="timeFormat" ></timeselector>
                        </div>
                        <div class="col-md-2">Columna 6</div>
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

export default {
    components: {
        Datepicker,
        Timeselector,
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
            instructorAreas: [],
            programs: [],
            timeFormat:{h:1, m:5, s:10}

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
        }
    },
    async mounted() {
        this.areas = await areaApi.getAll()
        this.instructorAreas = await instructorAreasApi.getAll()
        this.programs = await programsApi.getAll()
    },
    methods: {
        onNewClick() {
            this.displayForm = !this.displayForm
        },
        myFormatter(date) {
            moment.locale('es');
            return moment(date).format('d-MMM-yyyy');
        }


    }
}
</script>
