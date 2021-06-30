<template>
    <div>
        <div class="row">
                <div class="col-md-6 mt-4">
                    <h3> Buscar Aulas Virtuales Libres</h3>
                </div>
        </div>
        <div class="ml-2 mt-1 mr-1" >
            <div class="row">

                <div class="col-md-2 mt-4">
                    Fecha:
                    <input class="form-control" type="Date" v-model="freeDate" @change="onStartDateChange"/>
                </div>
                <div class="col-md-2 mt-4">
                    Inicia:
                    <input class="form-control text-center" type="time" v-model="freeStartTime" @change="onEndDateChange"/>
                </div>
                <div class="col-md-2 mt-4">
                    Termina:
                    <input class="form-control text-center" type="time" v-model="freeEndTime" @change="onEndDateChange"/>
                </div>

                 <div class="col-md-3 mt-5">
                        <button  class="btn btn-warning" @click="onClickAddFreeTime(freeDate,freeStartTime,freeEndTime)">Añadir Horario</button>
                </div>
            </div>
                <div class="row">
                    <div class="col-md-3 free-dates-list mt-4">
                        <h5 class="title mt-1 ml-1">
                            <font-awesome-icon  icon="calendar-day"/> Horarios Seleccionados
                        </h5>
                        <ul>
                            <li class="mt-2" v-for="(date, index) in sortedFreeDates" :key='index'>
                                <strong> {{ date.freeDate   }} </strong> de
                                {{ formatBookingTime( date.freeStartTime)   }} a
                                {{ formatBookingTime(date.freeEndTime)  }}
                                <a href='#' class="edit text-danger ml-3" @click="onDeleteFreeDate(date.freeDate,date.freeStartTime,date.freeEndTime)"><i class="fa fa-trash"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>


        </div>

        <div class="row mt-4" >
            <div class="col-md-1" >
                <button  class="btn btn-success" @click="onClickSearchFreeVirtualRooms()">Buscar</button>
            </div>
            <div class="col-md-10 p-3 mb-2 bg-info text-black" >
                    Aquí se mostrarán las Aulas Virtuales disponibles de acuerdo a las fechas y horarios seleccionados arriba.
            </div>
        </div>

        <div class="row mt-2" v-if="showAvailableVirtualRooms">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead class="thead-dark" >
                        <tr>
                            <th> Fecha </th>
                            <th> Inicia</th>
                            <th> Termina </th>
                            <th> Aulas Virtuales Disponibles </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="fdate in  freeDatesWithVR" :key="fdate.index" >
                            <td >
                                {{ formatBookingDate( fdate.freeDate) }}

                            </td>
                            <td  >
                                {{ formatBookingTime( fdate.freeStartTime) }}
                            </td>
                            <td >
                                {{  formatBookingTime(fdate.freeEndTime)  }}
                            </td>
                            <td>
                                {{  fdate.availableVR  }}
                            </td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>





import virtualRoomApi from '../services/virtualroom'

import moment from 'moment'
// import { filter } from 'vue/types/umd'





export default {
    components: {



    },
    data() {
        return {

            freeDate: moment().format("YYYY-MM-DD"),
            freeStartTime: null,
            freeEndTime: null,


            freeDates: [],

            freeDatesWithVR: [],



        }
    },
    computed: {
        sortedFreeDates() {
            return this.freeDates.sort((a, b) => a.start > b.start);
        },
        showAvailableVirtualRooms (){
            return this.freeDatesWithVR.length>0 ? true : false
        },


    },
    async mounted() {



    },
    methods: {
        onClickAddFreeTime(newDate,newStartTime,newEndTime){

            this.freeDates.push({
                    freeDate: moment(newDate).startOf('day').toDate().format('YYYY-MM-DD'),
                    freeStartTime: moment(newStartTime,"hh:mm").toDate(),//.format("HH:mm"),
                    freeEndTime: moment(newEndTime,"hh:mm").toDate(),//.format("HH:mm"),

                    //label: moment(e).format("DD/MM/YYYY"),
                })

        },



        onDeleteFreeDate(dateToDelete, startTimeToDelete, endTimeToDelete){


            this.freeDates = this.freeDates.filter(
                      (e) => !( moment(this.formatBookingDate(e.freeDate)).isSame(moment(this.formatBookingDate(dateToDelete)))
                                &&  moment(e.freeStartTime).isSame(moment(startTimeToDelete))
                                &&  moment(e.freeEndTime).isSame(moment(endTimeToDelete))
                            )
                  )

            this.freeDatesWithVR = this.freeDatesWithVR.filter(
                      (e) => !( moment(this.formatBookingDate(e.freeDate)).isSame(moment(this.formatBookingDate(dateToDelete)))
                                &&  moment(e.freeStartTime).isSame(moment(startTimeToDelete))
                                &&  moment(e.freeEndTime).isSame(moment(endTimeToDelete))
                            )
                  )



        },

        async onClickSearchFreeVirtualRooms(){

            this.freeDatesWithVR = []

            //Retrieve Virtual Rooms that are busy in the specified days and hours from database
            let busyVirtualRooms = await virtualRoomApi.getAvailableVirtualRooms(this.sortedFreeDates)

            //Retieve all Virtual Rooms
            let allVirtualRooms = await virtualRoomApi.getAll()

            //Extract ID from all Virtual Rooms
            var allVirtualRoomsMnemonics =[]
            allVirtualRooms.forEach( vr=> {
                allVirtualRoomsMnemonics.push(vr.mnemonic)
            })


            this.freeDates.forEach(f => {

                var busyVRforThisDate = busyVirtualRooms.filter( b => moment(this.formatBookingDate(b.booking_date)).isSame(moment(this.formatBookingDate(f.freeDate)))
                                                                    &&  ( (moment(b.start_time).isSameOrAfter(moment(f.freeStartTime).add(-1,'hours'))
                                                                          && moment(b.end_time).isSameOrBefore(moment(f.freeEndTime).add(1,'hours'))
                                                                          ) || ( moment(b.start_time).isSameOrAfter(moment(f.freeStartTime).add(-1,'hours'))
                                                                                && moment(b.start_time).isSameOrBefore(moment(f.freeEndTime).add(1,'hours'))
                                                                                )
                                                                            || ( moment(b.end_time).isSameOrAfter(moment(f.freeStartTime).add(-1,'hours'))
                                                                                && moment(b.end_time).isSameOrBefore(moment(f.freeEndTime).add(1,'hours'))
                                                                                )
                                                                            || ( moment(b.start_time).isSameOrBefore(moment(f.freeStartTime).add(-1,'hours'))
                                                                                && moment(b.end_time).isSameOrAfter(moment(f.freeEndTime).add(1,'hours'))
                                                                                )

                                                                        )

                                                               )

                //Extract busy virtual Room from bookings in that corresponds to this day and hour
                var busyVirtualR=[]
                busyVRforThisDate.forEach(busyVR => {
                        busyVirtualR.push(busyVR.vr_mnemonic)
                })


                //Get Available Virtual Rooms for this day and hour looking for which are not busy.
                var availableVR = []
                availableVR = allVirtualRoomsMnemonics.filter( vr => !busyVirtualR.includes(vr) )

                //Add avaiable virtual Rooms for this day and hour
                var availableVRstr = ""

                availableVR.forEach( avr=> {
                        availableVRstr = availableVRstr + "     " + avr
                })


                this.freeDatesWithVR.push( { freeDate: f.freeDate ,
                                             freeStartTime: f.freeStartTime,
                                             freeEndTime: f.freeEndTime,
                                             availableVR: availableVRstr,
                                        })
                //f.availableVR = availableVRstr
                console.log(" Available VR",availableVRstr )
            });

            console.log("FreeDates", this.freeDates)

        },



        async  onStartDateChange(){

        },

        async  onEndDateChange(){

        },

        formatBookingTime(value){
            return moment(value).toDate().format("HH:mm")
        },



        formatBookingDay(value){
            moment.locale("es");
            return moment(value).format("dddd")
        },

        formatBookingDate(value){
            moment.locale("es");
            return moment(value).format("yyyy-MM-DD")
        },

        formatBookingTime(value){
          // return moment(value,"HH:mm")

           return moment(value).toDate().format("HH:mm")
        },


    }
}
</script>

<style scoped>
div.free-dates-list {
    height: 200px;
    overflow: scroll;
}

div.free-dates-list ul {
    list-style-type: none;
}
</style>


