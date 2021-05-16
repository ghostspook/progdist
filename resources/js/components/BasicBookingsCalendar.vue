<template>
    <div>
        <div class="row">
            <div class="col-md-12 ml-4">
                Semana del:
                <input type="Date" v-model="startDate" @change="onDateChange"/>
            </div>
        </div>
        <div></div>
        <div class="row mt-4" >
            <div class="col-md-12 ml-2" v-html="basicCalendar" :key="calendarKey">

            </div>
        </div>

    </div>
</template>

<script>
import VueCal from 'vue-cal'
import 'vue-cal/dist/i18n/es.js'
import 'vue-cal/dist/vuecal.css'
import bookingsApi from '../services/booking'

import moment from 'moment'


const ROLE_COORD = 1;
const ROLE_ACAD = 2;
const ROLE_TI = 3;

const SUPPORT_TYPE_PHYSICAL = 0;
const SUPPORT_TYPE_VIRTUAL = 1;



export default {
    components: {
        VueCal,

    },
    data() {
        return {
            bookings: [],
            startDate: moment().format("YYYY-MM-DD"),
            calendarKey: 0,
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
           // var from = this.startDate


            for (i=1;i<=7;i++){

                //this.bookings.forEach ( bk => { console.log ("book", moment( bk.booking_date).format('YYYY-MM-DD'))})

                calendarHTMLHeader = '<table class="table table-striped">' +
                                        '<thead class="thead-dark" >' +
                                            '<tr>' +
                                                `<th> ${ moment(from).format("dddd")} ${ moment(from).format("DD-MMM-YYYY")} </th>`+
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

                console.log ("Este día",from)
                thisDayBookings.forEach( book => {
                                    var classPrefix = "vuecal__event "
                                    var programClass = book.program.class? classPrefix + book.program.class : ""
                                    console.log( "class", programClass)
                                    calendarHTMLBody= calendarHTMLBody + '<tr>' +
                                                                    '<td>' +
                                                                       `<div class="${programClass}" >` +
                                                                        book.program.mnemonic +
                                                                        ( (! book.topic) ? "" :  book.topic ) +
                                                                        '</div>' +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                       ( (! book.instructor) ? "" :  book.instructor.name )  +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                       ( (! book.physical_room) ? "" :  book.physical_room.mnemonic ) +
                                                                    '</td>' +
                                                                     '<td>' +
                                                                        ( (! book.virtual_meeting_link) ? "" :
                                                                        book.virtual_meeting_link.virtual_room.mnemonic ) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                        this.formatBookingTime(book.start_time) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                        this.formatBookingTime(book.end_time) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                         ( (! book.virtual_meeting_link) ? "" :
                                                                        book.virtual_meeting_link.link ) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                         ( (! book.virtual_meeting_link) ? "" :
                                                                        book.virtual_meeting_link.password ) +
                                                                    '</td>' +
                                                                    '<td>' +
                                                                         ( (! book.booking_support_persons) ? "" :
                                                                        this.supportPeople(book.booking_support_persons)) +
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
        console.log("startDate", this.startDate)
        await this.fetchBookings()

        console.log("this bookings", this.bookings)




    },
    methods: {


        async fetchBookings() {
            var from = null
            var to = null
            from = moment(this.startDate).startOf('isoWeek')
            to = moment(this.startDate).endOf('isoWeek')
            console.log("Inicio de la semana", from)
            this.bookings = await bookingsApi.getByDateSpan(from.format('YYYY-MM-DD'), to.format('YYYY-MM-DD'))

           // console.log("Fetch booking",this.bookings)
        },



        async refreshCalendar(e) {
            var startDate = null
            var endDate = null
            if (this.active_view == "week") {
                startDate = moment(e.start).startOf('isoWeek')
                endDate = moment(e.start).endOf('isoWeek')
            } else if (this.active_view == "month") {
                startDate = moment(e.start).startOf('month')
                endDate = moment(e.start).endOf('month')
            }
            await this.fetchEvents({startDate: startDate, endDate: endDate})
        },

     async   onDateChange(){
            console.log("Nueva Fecha",this.startDate)
            await this.fetchBookings()
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

        supportPeople (sp){
            var supportStr = ""
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
        }


    }
}
</script>

<style scoped>




</style>

<style>
.vuecal__event.orange {background-color: rgba(253, 156, 66, 0.9);border: 1px solid rgb(233, 136, 46);color: #fff;}
.vuecal__event.green {background-color: rgba(164, 230, 210, 0.9);border: 1px solid rgb(144, 210, 190);}
.vuecal__event.red {background-color: rgba(255, 102, 102, 0.9);border: 1px solid rgb(235, 82, 82);color: #fff;}
.vuecal__event.blue {background-color: rgba(102, 181, 255, 0.9);border: 1px solid rgb(102, 181, 255);color: #fff;}
.vuecal__event {background-color: rgba(182, 191, 201, 0.9);border: 1px solid rgb(182, 191, 201);color: #fff;}

</style>

