<template>
    <div>
        <div class="row">
            <div class="col-md-4 ml-4 mt-4">
                Semana del:
                <input type="Date" v-model="startDate" @change="onDateChange"/>
            </div>
            <div class="col-md-6">
                Ordenar por:
                <multiselect
                    id="calendarOrdering"
                    v-model="selectedOrdering"
                    :options="selectableOrdering"
                    @input="onOrderingChange"
                    track-by="orderBy"
                    label="orderBy"
                    :multiple="true"
                    :taggable="true"
                    :showLabels="true"
                    :hide-selected="true"
                ></multiselect>
            </div>
        </div>

        <div class="row mt-4" >
            <div class="col-md-12 ml-2" v-html="basicCalendar" :key="calendarKey">

            </div>
        </div>

    </div>
</template>

<script>

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

import bookingsApi from '../services/booking'
import instructorsApi from '../services/instructor'

import moment from 'moment'





export default {
    components: {
        Multiselect,

    },
    data() {
        return {
            bookings: [],
            instructorConstraints: [],
            startDate: moment().format("YYYY-MM-DD"),
            calendarKey: 0,
            selectedOrdering: [],
            selectableOrdering: [],
        }
    },
    computed: {
         basicCalendar(){
            moment.locale("es");
            var calendarHTML = ""
            var calendarHTMLBody= ""
            var calendarHTMLHeader=""
            var calendarHTMLTail = ""
            var from= moment(this.startDate).startOf('isoWeek').format('YYYY-MM-DD')

            var i;
            var thisDayBookings = []

            //Look for Instructor Constraints



            for (i=1;i<=7;i++){



                calendarHTMLHeader = '<table class="table table-striped">' +
                                        '<thead class="thead-dark" >' +
                                            '<tr>' +
                                                `<th> ${ moment(from).format("dddd").toUpperCase()} ${ moment(from).format("DD-MMM-YYYY")} </th>`+
                                           '</tr>' +
                                            '<tr>' +
                                                '<th> Programa</th>' +
                                                '<th> Profesor </th>'  +
                                                '<th> Aula Física </th>' +  '<th> Aula Virtual </th>' +
                                                '<th> Inicia </th>' +  '<th> Termina </th>' +
                                                '<th> Link </th>' +  '<th> Contraseña </th>' +
                                                '<th> Soporte </th>' +
                                            '</tr>' +
                                        '</thead>' +
                                        '<tbody>'

                thisDayBookings = this.bookings.filter( (b) => moment(b.booking_date).isSame(from,'day'))


                thisDayBookings.forEach( book => {
                                    var classPrefix = "vuecal__event "
                                    var programClass = book.program_class? classPrefix + book.program_class : ""
                                    console.log( "class", programClass)
                                    calendarHTMLBody= calendarHTMLBody + '<tr>' +
                                                                    '<td>' +
                                                                       `<div class="${programClass}" >` +
                                                                        book.program +
                                                                        ( (! book.topic) ? "" :  book.topic ) +
                                                                        '</div>' +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                       ( (! book.instructor_name) ? "" :  book.instructor_name )  +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                       ( (! book.physical_room) ? "" :  book.physical_room ) +
                                                                    '</td>' +
                                                                     '<td>' +
                                                                        ( (! book.virtual_room) ? "" :
                                                                        book.virtual_room ) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                        this.formatBookingTime(book.start_time) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                        this.formatBookingTime(book.end_time) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                         ( (! book.link) ? "" :
                                                                        book.link ) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                         ( (! book.password) ? "" :
                                                                        book.password ) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                         ( (! book.support) ? "" :
                                                                            book.support) +
                                                                    '</td>' +


                                                                '</tr>'

                                })



                calendarHTMLTail = ' </tbdoy> </table>'
                calendarHTML = calendarHTML + calendarHTMLHeader + calendarHTMLBody + calendarHTMLTail
                calendarHTMLBody = ""
                from = moment(from).add(1,'days').format('YYYY-MM-DD')

            }


            console.log("this day Bookings", thisDayBookings)
            return calendarHTML
        },




    },
    async mounted() {
        await this.fetchBookings()

        await this.fetchInstructorConstraints()
        console.log("Constraints", this.instructorConstraints)

        this.selectableOrdering.push({orderBy: "Aula Física"})
        this.selectableOrdering.push({orderBy: "Aula Virtual"})
        this.selectableOrdering.push({orderBy: "Hora de Inicio"})
        this.selectableOrdering.push({orderBy: "Profesor"})
        this.selectableOrdering.push({orderBy: "Programa"})




        console.log("this bookings", this.bookings)







    },
    methods: {


        async fetchBookings() {
            var from = null
            var to = null
            var orderBy={}
            from = moment(this.startDate).startOf('isoWeek')
            to = moment(this.startDate).endOf('isoWeek')
            orderBy = JSON.stringify(this.selectedOrdering)
            console.log("Inicio de la semana", from)
            console.log(orderBy)

          console.log("Estos bookings", this.bookings)
          this.bookings = await bookingsApi.getByWeek(from.format('YYYY-MM-DD'), to.format('YYYY-MM-DD'), orderBy)

           // console.log("Fetch booking",this.bookings)
        },

        async fetchInstructorConstraints (){
            var from = null
            var to = null

            from = moment(this.startDate).startOf('isoWeek')
            to = moment(this.startDate).endOf('isoWeek')

            console.log("from Constraint", from)

            this.instructorConstraints = await instructorsApi.getInstructorConstraints(from.format('YYYY-MM-DD'), to.format('YYYY-MM-DD'))


        },





        async  onDateChange(){
            console.log("Nueva Fecha",this.startDate)
            await this.fetchBookings()
            this.fetchInstructorConstraints()
            console.log("Constraints", this.instructorConstraints)
            console.log("Bookings", this.bookings)
            this.forceRerender()
        },

        formatBookingTime(value){
            return moment(value).toDate().format("HH:mm")
        },

        toSupportRoleText(value) {
            switch (value) {
                case 1:
                    return 'Coord. Acad.'
                case 2:
                    return 'Soprte. Acad.'
                case 3:
                    return 'Soprte. TI'
                default:
                    return '?'
            }
        },
        toSupportTypeText(value) {
            switch (value) {
                case 0:
                    return 'Físico'
                case 1:
                    return 'Virtual'
                default:
                    return '?'
            }
        },

        async supportPeople (){
            var supportStr = ""
            var sp= []



            console.log("personas de soporte",sp)

            sp.forEach( p => {

                    supportStr = supportStr + "<p>" + "<b>" + this.toSupportRoleText (p.support_role) +
                    " : " + "</b>" + p.support_person.name + " - " + this.toSupportTypeText (p.support_type)
                    + "</p>"

                })

           console.log("support",supportStr)
            return supportStr
        },

        forceRerender(){
            this.calendarKey +=1
        },

        async onOrderingChange (){

                await this.fetchBookings()


        }


    }
}
</script>

<style scoped>




</style>

<style>
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


.vuecal__event {background-color: rgba(182, 191, 201, 0.9);border: 1px solid rgb(182, 191, 201);color: black;}

</style>

