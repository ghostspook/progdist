<template>
    <div>
        <div class="card">
            <h5 class="card-header">
                Bloque desde las 
                <span class="mr-1 bg-primary text-white">{{ booking.start_time }} </span > 
                hasta las 
                <span class="bg-primary text-white">{{ booking.end_time }}</span>
            </h5>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="row">
                    <div class="my-calendar col-md-6" >
                   
                        <div class="row justify-content-center mt-4">
                            <button  :disabled="splitting" class="btn btn-warning mt-2" >Split Ahora!</button>
                        </div>
                    </div>
                        <div class="col-md-6 ">

                       

                               


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>


<script>
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
            splitting: false
        }
    },
    filters: {
        
    },
    computed: {

  

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
        
    

        
   


      


    }


}
</script>

<style>

</style>