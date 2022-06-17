<template>

   <div class="ml-2 mr-2">
        <div v-if="!loadingSpinner">
            <div class="row">
                <!-- <div class="col-md-1">
                    <label for="year"> Año</label>
                </div> -->
                <!-- <div class="col-md-2">
                        <select v-model="params.year" @change="onChangeYear()">
                            <option v-for="(n,index) in 10" :key="index"> {{ 2020 + index}}</option>
                        </select>
                </div> -->


                <div class="col-md-9 mb-2 d-flex d-flex flex-row">

                    <label for="Desde" class="w-25 col-md-2 col-form-label col-form-label-lg text-right">Mostrando registros </label>
                    <label for="Desde" class="w-25 col-md-1 col-form-label col-form-label-lg text-right">Desde</label>
                    <input  v-model="params.from" type="date"
                    class="form-control col-md-2"/>


                    <label for="Hasta" class="col-md-1 col-form-label col-form-label-lg text-right">Hasta</label>
                    <input  v-model="params.to"   name="toBookingDate"
                    type="date" class="form-control col-md-2"/>

                    <div class="col-md-2">
                        <button  class="btn btn-success mb-3" @click="onDateRangeChange()">
                            Consultar
                        </button>
                    </div>

                </div>



            </div>
            <div class="row">
                <div class="col-md-2 mb-2 d-flex d-flex flex-row">
                    <label class="col-form-label col-form-label-lg text-right">  Filtrar Profesores:</label>
                </div>

                <div class="col-md-8">
                    <multiselect
                        id="calendarOrdering"
                        v-model="params.selectedInstructors"
                        :options="selectableInstructors"
                        @input="onFilterInstructorsChange"
                        track-by="name"
                        label="name"
                        :multiple="true"
                        :taggable="true"
                        :showLabels="true"
                        :hide-selected="true"
                    ></multiselect>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div v-if="loadingSpinner" class="col-md-12 d-flex justify-content-center " >
                    <pacman-loader :loading="loadingSpinner"> </pacman-loader>
                    Cargando
            </div>
        </div>

        <div class="row ml-2 mr-2 ">

            <div>
                <table v-if="!loadingSpinner" class="table table-striped">
                    <thead style="position: sticky;top: 0" class="thead-dark">
                        <th style="position: sticky;left: 0" class="thead-dark"> Día </th>
                        <!-- <th style="position: sticky;left: 0" class="thead-dark"> Fecha </th> -->
                        <th class="text-center" v-for="(instructor,index) in bookedInstructors" :key="index">
                            <div>{{instructor.mnemonic}}</div>
                            <div>{{instructor.name}}</div>
                        </th>
                    </thead>
                    <tr v-for="(d) in programmingDates" :key="d.index" :class="dayClass(d.isSunday)">
                        <td style="position: sticky;left: 0" class="text-light bg-dark">
                            {{ d.day}} {{ d.dateString}}
                        </td>
                        <!-- <td style="position: sticky;left: 0" class="thead-dark">
                            {{ d.dateString}}
                        </td> -->
                        <td v-for="i in d.instructors" :key="i.index">
                            <div v-for="p in i" :key="p.index" class="text-center" :class="programClass(p.class)">
                                {{ p.mnemonic}}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

</template>

<script>



import bookingApi from "../services/booking";
import instructorsApi from "../services/instructor";



import moment from "moment";
// import { Remarkable } from 'remarkable'


import PacmanLoader from 'vue-spinner/src/PacmanLoader.vue'

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";


export default {
  components: {  PacmanLoader },

data() {
    return {

        loadingSpinner : false,
        programmingDates: [],
        overallProgramming: [],
        bookedInstructors: [],
        selectableInstructors: [],

        params: {
            from: null,
            to: null,
            selectedInstructors: [],
        }
    }
},

computed: {

},

    async mounted(){
        moment.locale("es");
        this.params.from = moment().startOf('year').toDate().toISOString().substr(0,10)
        this.params.to = moment().endOf('year').subtract(1,'day').toDate().toISOString().substr(0,10)

        console.log("params",this.params)

        await this.fetchInstructors()
        await this.getOverallProgramming()

        console.log ("selectable Instructors",this.selectableInstructors)
        this.loadProgrammingDates()

    },
    methods: {

        async getOverallProgramming() {

            this.loadingSpinner = true
            try {
                this.params.instructor="NN"
                this.overallProgramming = await bookingApi.getOverallProgramming(this.params)
                this.bookedInstructors = this.overallProgramming.instructors
                 console.log("Booked Instructors",this.bookedInstructors)
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la programación general"
                });
            }


        },


        async onDateRangeChange (){
            this.loadingSpinner = true
            await this.getOverallProgramming()
            this.loadProgrammingDates()

        },

        async onFilterInstructorsChange() {
            this.loadingSpinner = true
            await this.getOverallProgramming()
            this.loadProgrammingDates()


        },

        loadProgrammingDates() {

            this.loadingSpinner = true

            this.programmingDates.splice(0, this.programmingDates.length);
            var startDate, endDate;

            startDate = moment (this.params.from )
            endDate = moment (this.params.to )

            var currentDate = startDate

            var programs
            while ( currentDate <= endDate) {
                var instructorsSessions= []

                this.bookedInstructors.forEach(i => {

                    programs = this.overallProgramming['sessions'].filter( s => s.instructor_id == i.instructor_id &&
                                                                                    moment(s.booking_date).isSame(moment(currentDate)))

                    var n = []
                    programs.forEach(x => {
                        n.push (x.program)

                    });

                    instructorsSessions.push ( n)




                });


                this.programmingDates.push( {'date' : currentDate ,
                                'day' :  moment(currentDate).format('dddd'),
                                'isSunday' : moment(currentDate).day() == 0 ? true : false,
                                'dateString' : moment(currentDate).format('DD/MMM') ,
                                'instructors' : instructorsSessions
                                });


                currentDate = currentDate.add(1,'day')
            }





             this.loadingSpinner = false

        },

        bookedProgramsForInstructor (date, instructorId) {
            //this.loadingSpinner =false
            return this.overallProgramming['sessions'].filter( s => s.instructor_id == instructorId &&
                                                                moment(s.booking_date).isSame(moment(date)))








        },

        async fetchInstructors() {
            try {
                this.selectableInstructors = await instructorsApi.getAll()
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

        programClass (pClass){
            var classPrefix = "vuecal__event "
            return pClass ? classPrefix + pClass : ""

        },

        dayClass(isSunday){
            return isSunday ? "bg-warning text-dark" : ""

        }







    }
}
</script>

<style scoped>
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

