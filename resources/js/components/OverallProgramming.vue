<template>
   <div class="ml-2 mr-2">
       <div v-if="popoverDetails" style="width: 400;">

            <div class="bg-dark text-white" :style="bookedProgramDetailsStyle">
                <div>{{ bookedProgramDetails[0]? bookedProgramDetails[0].program.name : ""}}</div>
                <div>{{ bookedProgramDetails[0]? bookedProgramDetails[0].instructor.name : ""}}</div>
                    <div v-for="session in bookedProgramDetails" :key="session.index">
                        <div>{{ session.area.name }}</div>
                        <div>Desde las {{ session.start_time | toTimeString }} Hasta las {{ session.end_time | toTimeString }}</div>
                    </div>
            </div>
        </div>




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

                    <label class="col-md-2 col-form-label col-form-label-lg">Mostrando registros </label>
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
                    <label class="ml-2 col-form-label col-form-label-lg pull-right">  Filtrar Áreas:</label>
                </div>

                <div class="col-md-4">
                    <multiselect
                        id="calendarOrdering"
                        v-model="params.selectedAreas"
                        :options="sortedAreas"
                        @input="onFilterAreasChange"
                        track-by="name"
                        label="name"
                        :multiple="true"
                        :taggable="true"
                        :showLabels="true"
                        :hide-selected="true"
                    ></multiselect>
                </div>
                <div class="col-md-2 mb-2 d-flex d-flex flex-row pull-right">
                    <label class="col-form-label col-form-label-lg text-right">  Filtrar Profesores:</label>
                </div>

                <div class="col-md-4 pull-left">
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

            <hr/>
            <div class="row">
                <div class="col-md-2 mb-2 d-flex d-flex flex-row">
                    <button  class="ml-2 btn btn-success mb-3" @click="exportToExcel()">
                        Exportar a Excel
                    </button>
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
                <table v-if="!loadingSpinner" class="table table-striped" @click="popoverDetails=false">
                    <thead style="position: sticky;left: 0;top: 0; zIndex:2" class="thead-dark">
                        <th class="text-center" style="position: sticky;left: 0;top: 0; zIndex:2"> Día </th>
                        <!-- <th style="position: sticky;left: 0" class="thead-dark"> Fecha </th> -->
                        <th class="text-center" v-for="(instructor,index) in bookedInstructors" :key="index">
                            <div>{{instructor.mnemonic}}</div>
                            <div>{{instructor.name}}</div>
                        </th>
                    </thead>
                    <tr v-for="(d,dateIndex) in programmingDates" :key="dateIndex" :class="dayClass(d.isSunday)">
                        <td style="position: sticky;left:0; zIndex:2" class="text-light bg-dark">
                            {{ d.day}} {{ d.dateString}}
                        </td>
                        <!-- <td style="position: sticky;left: 0" class="thead-dark">
                            {{ d.dateString}}
                        </td> -->
                        <td v-for="i in d.instructors" :key="i.index">
                            <div v-if="i.constrained" class="alert alert-danger" role="alert">
                                    BLOQUEO
                            </div>
                            <div v-for="(p,programIndex) in i" :key="programIndex"  class="text-center"  :class="programClass(p.class)"
                                @mouseenter="onBookedProgramHover($event,d.fullDate,i.instructor_id,p.id)"
                                >
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
import areaApi from "../services/area";
import instructorsApi from "../services/instructor";



import moment from "moment";
// import { Remarkable } from 'remarkable'


import PacmanLoader from 'vue-spinner/src/PacmanLoader.vue'


import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

import XLSX from 'sheetjs-style';


const EXPORT_CELL_COLORS = [
    { css: 'orange' , xlsx: { font: 'FFFFFF' , background: 'FC9C42'}  },
    {css: 'dark_orange' , xlsx: { font: 'FFFFFF' , background: '7C4615'} },
    {css: 'green' , xlsx: { font: '000000' , background: 'A4E5D1'} },
    {css: 'dark_green' , xlsx: { font: 'FFFFFF' , background: '034B35'} },
    {css: 'red' , xlsx: { font: 'FFFFFF' , background: 'FE6565'} },
    {css: 'dark_red' , xlsx: { font: 'FFFFFF' , background: '570707'} },
    {css: 'blue' , xlsx: { font: 'FFFFFF' , background: '65b4FE'} },
    {css: 'dark_blue' , xlsx: { font: 'FFFFFF' , background: '012F65'} },
    {css: 'fucsia' , xlsx: { font: 'FFFFFF' , background: 'C827C0'} },
    {css: 'wine' , xlsx: { font: 'FFFFFF' , background: '350202'} },
    {css: 'purple' , xlsx: { font: 'FFFFFF' , background: '4B0E48'} },
    {css: 'mamey' , xlsx: { font: 'FFFFFF' , background: 'F9A85D'} },
    {css: 'dark_gray' , xlsx: { font: 'FFFFFF' , background: '4D4443'} },
]


export default {
  components: {  PacmanLoader  },

data() {
    return {

        loadingSpinner : false,
        programmingDates: [],
        overallProgramming: [],
        bookedInstructors: [],
        areas: [],
        selectableInstructors: [],

        bookedProgramDetails: [],

        params: {
            from: null,
            to: null,
            selectedInstructors: [],
            selectedAreas: [],
        },

        popoverDetails: false,



    }
},

computed: {
    sortedAreas() {
        return this.areas.sort((a, b) => a.mnemonic > b.mnemonic);
    },


    bookedProgramDetailsStyle (){
        return "position: absolute;" +
                "top:" + this.bookedProgramDetails.clientY  + 'px;' +
                "left:" + this.bookedProgramDetails.clientX  + 'px;' +
                "width: 450px;" +
                "border: 3px solid #73AD21;" +
                "z-index: 3;"



        },


    },
filters: {

        toTimeString(bookingTime){
            return moment(bookingTime).format("HH:mm")
        },



    },

    async mounted(){
        moment.locale("es");
        this.params.from = moment().startOf('year').toDate().toISOString().substr(0,10)
        this.params.to = moment().endOf('year').subtract(1,'day').toDate().toISOString().substr(0,10)


        await this.fetchAreas()
        await this.fetchInstructors()
        await this.getOverallProgramming()


        this.loadProgrammingDates()

    },
    methods: {

        async exportToExcel(){

            var rows= []
            var row = {}
            var styles=[]

            // START OF Restructure Programming Dates adding one column for each of the instructors
            var programmedDates = []
            var programmedDate ={}

            this.programmingDates.forEach(bookedDate => {
                bookedDate.instructors.forEach((instructor,index) => {
                    programmedDate['date'] = bookedDate.fullDate
                    programmedDate[this.bookedInstructors[index].mnemonic + ' ' +
                                    this.bookedInstructors[index].name
                                ]= instructor
               });
               programmedDates.push (programmedDate)
               programmedDate = {}
            });

            // END OF Restructure ...


            // Iterate the restructured array.
            programmedDates.forEach(programmedDate => {
                var programsQty = []
                var maxProgrammsPerInstructor = 0

                //In each date calculate the instructor with more booked programs
                //in order to know how many rows of the same date must be added
                this.bookedInstructors.forEach(bookedInstructor => {
                    programsQty.push ( programmedDate[bookedInstructor.mnemonic + ' ' +
                                        bookedInstructor.name
                                        ].length)

                    row['date'] = programmedDate['date']
                    row[bookedInstructor.mnemonic + ' ' + bookedInstructor.name] =''
                });
                maxProgrammsPerInstructor = Math.max (...programsQty)

                //Check if in the current date there are no booked programs for any instructor,
                //in that case, a blank row for this date must be added
                if (maxProgrammsPerInstructor==0) {
                    rows.push (row)
                    row ={}
                }

                //Iterate again all the instructors in the current day as many times as
                //the instructor with more programms booked in the same date
                for (var i=0; i < maxProgrammsPerInstructor; i++){
                    this.bookedInstructors.forEach( (bookedInstructor,index ) => {
                        if ( programmedDate[bookedInstructor.mnemonic + ' ' +
                                        bookedInstructor.name][i]){
                            row['date'] = programmedDate['date']
                            console.log("Date", programmedDate['date'])
                            row[bookedInstructor.mnemonic + ' ' + bookedInstructor.name] =
                                programmedDate[bookedInstructor.mnemonic + ' ' + bookedInstructor.name][i].mnemonic

                            //Add Styles
                            var programClass = programmedDate[bookedInstructor.mnemonic + ' ' + bookedInstructor.name][i].class

                            var style = { R: rows.length+1, C: index+1, color: programClass, encodedCell: '' }
                            //R: length +1 because of the column headers
                            //C: index + 1 because the first column is for date

                            //encode cell styles in XLSX cell notation
                            var cell_address = {c: style.C, r: style.R};
                            var cell_ref = XLSX.utils.encode_cell(cell_address);
                            style.encodedCell =cell_ref

                            styles.push (style)
                        }
                    });
                    rows.push (row)
                    row ={}
                }
            });


            /* generate worksheet and workbook */
            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "Programación General");

            /* fix headers */
            let instructorsColumns=[]
            instructorsColumns.push("Fecha")
            this.bookedInstructors.forEach(i => {

                instructorsColumns.push(i.mnemonic + ' ' + i.name)

            });


            XLSX.utils.sheet_add_aoa(worksheet, [  instructorsColumns ], { origin: "A1" });


            //apply styles
            styles.forEach(cell => {
                var backgroundColor = EXPORT_CELL_COLORS.filter( color => color.css == cell.color ).length>0 ?
                                      EXPORT_CELL_COLORS.filter( color => color.css == cell.color )[0] : ''

                if (backgroundColor != '') {

                    if ( worksheet[cell.encodedCell]){
                        worksheet[cell.encodedCell].s =  { font: {
                                                                color: { rgb: backgroundColor.xlsx.font },
                                                                bold:true
                                                                },
                                                            fill: {
                                                                fgColor: { rgb: backgroundColor.xlsx.background },
                                                            }
                                                        }
                    }
                }
            });



            /* create an XLSX file and try to save to  */
            XLSX.writeFile(workbook, "ProgDist - Programación General.xlsx");
        },

        async getOverallProgramming() {

            this.loadingSpinner = true
            this.popoverDetails = false
            try {
                this.params.instructor="NN"
                this.overallProgramming = await bookingApi.getOverallProgramming(this.params)
                this.bookedInstructors = this.overallProgramming.instructors

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

        async onFilterAreasChange() {
            this.loadingSpinner = true
            await this.getOverallProgramming()
            this.loadProgrammingDates()


        },





        async onBookedProgramHover(e,date,instructor,program){

            this.popoverDetails = true

            const s = await bookingApi.getOverallProgrammingSessionDetails( {booking_date: date, instructor_id: instructor, program_id: program})
            this.bookedProgramDetails = s['details']


            this.bookedProgramDetails.clientX = e.clientX + window.scrollX
            this.bookedProgramDetails.clientY = e.clientY + window.scrollY


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
                        n.instructor_id = i.instructor_id

                    });

                    //Search constraints for this instructor on this date
                    if (this.searchConstraints( currentDate,i.instructor_id)){

                        n.constrained = true
                    }


                    instructorsSessions.push ( n)



                });


                this.programmingDates.push( {'date' : currentDate ,
                                'day' :  moment(currentDate).format('dddd'),
                                'isSunday' : moment(currentDate).day() == 0 ? true : false,
                                'dateString' : moment(currentDate).format('DD/MMM') ,
                                'fullDate': moment(currentDate).format('YYYY-MM-DD') ,
                                'instructors' : instructorsSessions
                                });


                currentDate = currentDate.add(1,'day')
            }
            console.log("Programming Dates", this.programmingDates)





             this.loadingSpinner = false

        },

        searchConstraints (date,instructorId){
            return this.overallProgramming['constraints'].filter(c => c.instructor_id == instructorId &&
                                                                (moment(date).isSameOrAfter(moment(c.from))
                                                                && moment(date).isSameOrBefore(moment(c.to))
                                                                )).length > 0 ? true : false

        },

        bookedProgramsForInstructor (date, instructorId) {
            //this.loadingSpinner =false
            return this.overallProgramming['sessions'].filter( s => s.instructor_id == instructorId &&
                                                                moment(s.booking_date).isSame(moment(date)))


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

