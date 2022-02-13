<template>
    <div>
        <div class="card">
            <h5 class="card-header">
                Bloque desde las 
                <span class="mr-1 bg-success text-white">{{ booking.start_time }} </span > 
                hasta las 
                <span class="bg-success text-white">{{ booking.end_time }}</span>
            </h5>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-12 pull-left" >
                        <h5>Dividirlo así:</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <label for="splitStartTime">Inicia: </label>
                        <input v-model="splitStartTime" id="splitStartTime" type="time" min="06:00" max="23:55" required>
                    </div>
                    <div class="col-md-4">
                        <label for="splitEndTime">Termina: </label>
                        <input v-model="splitEndTime" id="splitEndTime" type="time" min="06:00" max="23:55" required>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" @click="onAddSplitClick()" >Agregar División</button>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-12">
                        <span class="alert alert-danger alert-dismissible fade show" v-if="timeError"> {{ errorMessage }}</span>
                    </div>
                    
                </div>
                <div class="row mt-4">
                    
                        <div class="col-md-12">
                            <h5 class="title mt-2 ml-1">
                            <!-- <font-awesome-icon icon="fa-solid fa-bars" /> -->
                            Divisiones creadas para este bloque
                            </h5>
                            <div class="alert alert-danger" role="alert" v-if="splittingBookings.length==0"> Ninguna</div>
                            
                            <ul>
                                <li class="mt-2" v-for="(split, index) in sortedSplittingBookings" :key='index'>
                                    De {{ split.startTime }} a {{ split.endTime }}
                                    <a href='#' class="edit text-danger ml-3" @click="onDeleteSplit(split)"><i class="fa fa-trash"></i></a>
                                </li>
                            </ul>
                        </div>
                    
                    
                </div>
                    <div class="row mt-3">
                        <div class="col-md-12" >
                            <div class="row justify-content-center mt-4">
                                <button  :disabled="splitting" class="btn btn-primary mt-2" @click="onSplitClick()" >¡Dividir Ahora!</button>
                            </div>
                        </div>
                  
                    </div>
                
            </div>
        </div>
    </div>
</template>


<script>
import moment from "moment";
import bookingsApi from '../services/booking';



export default {
    components: {
    
    },
    props: {
        booking: {
            type: Object,
            required: true
        },
   
    },
    data() {
        return {
            splitting: false,
            splitStartTime: Date,
            splitEndTime: Date,
            splittingBookings: [],
            timeError: false,
            errorMessage: "",

        }
    },
    filters: {
        
    },
    computed: {
        sortedSplittingBookings() {
            return this.splittingBookings.sort((a, b) => moment( a.startTime,"hh:mm").
                                                        isAfter(moment(b.startTime,"hh:mm")));
        },

  

    },
    
    mounted() {
        
        console.log("Booking", this.booking)

    },
    watch: {
        // bookingId:
        //     async function(val) {
        //         await this.fetchBooking()
        //     }
    },
    methods: {
        
        onAddSplitClick(){
            var startTime= moment(this.splitStartTime,"hh:mm")
            var endTime= moment(this.splitEndTime,"hh:mm")
            
            if (endTime.isBefore(startTime)) {
                this.timeError = true
                this.errorMessage = "La hora de inicio debe ser menor a la hora de fin"
                return         
            }
       
            var blockStartTime = moment(this.booking.start_time,"hh:mm")
            var blockEndTime = moment(this.booking.end_time,"hh:mm")
            
            console.log("booking", this.booking)

            if ( !startTime.isBetween(blockStartTime.subtract(1,"minutes"),blockEndTime.add(1,"minutes")) || 
                !endTime.isBetween(blockStartTime.subtract(1,"minutes"),blockEndTime.add(1,"minutes"))) {
                    
                this.timeError= true
                this.errorMessage = "Las horas de inicio y fin deben estar dentro del bloque"
                return
            } 


            this.splittingBookings.push( { 
                startTime: this.splitStartTime,
                endTime: this.splitEndTime,
                }
            )

            this.timeError= false
            this.errorMessage =""
            this.splitStartTime = null
            this.splitEndTime = null
            

        },

        onDeleteSplit(splitToDelete){
             console.log("splitToDelete",splitToDelete.startTime)
             
             this.splittingBookings = this.splittingBookings.filter(
                      (split) => ! (split.startTime == splitToDelete.startTime 
                                    && split.endTime == splitToDelete.endTime
                                    )
                  )
            console.log("SplittingBooking", this.splittingBookings)

        },

        async onSplitClick(){

            this.splitting = true
            self = this

            if (this.splittingBookings.length==0)
            {
                return
            }

             //Modify the original booking to be the first slot of splitted Booking
            try {
                var bookingObj = {
                            booking_date: moment(this.booking.booking_date).toDate(),
                            program: this.booking.program ? this.booking.program.id : null,
                            topic: this.booking.topic,
                            startTime: this.splittingBookings[0].startTime,
                            endTime: this.splittingBookings[0].endTime,
                            area: this.booking.area ?  this.booking.area.id : null,
                            instructor: this.booking.instructor ? this.booking.instructor.id : null,
                            physicalRoom: this.booking.physical_room ? this.booking.physical_room.id : null,
                            virtualRoom: this.booking.virtual_meeting ? this.booking.virtual_meeting.virtual_room_id : null,
                            supportPeople: this.booking.support_people,
                            link: this.booking.virtual_meeting ? this.booking.virtual_meeting.link_id : null,
                            virtualRoomCapacity: this.booking.virtual_room_capacity,
                    };


                var responseData = await bookingsApi.update( 
                                            this.booking.booking_id,
                                            {
                                                newBooking: bookingObj,
                                            }
                                        );


                //Create the rest of splitted bookings from the original booking
                for (var i = 1; i < self.splittingBookings.length ; i++) {
                    bookingObj.startTime = self.splittingBookings[i].startTime
                    bookingObj.endTime = self.splittingBookings[i].endTime
                    
                    responseData  = await bookingsApi.create(
                        {
                            newBooking: bookingObj,
                        }
                    );
                }
                this.$emit('booking-splitting-success')

            }
            catch(e) {
                console.log(e)
                console.log(e.response.data);
                this.$emit('booking-splitting-error', e)
            } 
            finally {
                this.splitting = false;
            }

            
            
        }

        
   


      


    }


}
</script>


<style scoped>
div.splitting-booking-list {
    height: 400px;
    overflow: scroll;
}

div.splitting-booking-list ul {
    list-style-type: none;
}
</style>