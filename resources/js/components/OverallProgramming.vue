<template>

   <div>

       <div class="row">
           <div class="col-md-1">
               <label for="year"> Año</label>
           </div>
           <div class="col-md-2">
                <select v-model="params.year" @change="onChangeYear()">
                    <option v-for="(n,index) in 10" :key="index"> {{ 2020 + index}}</option>
                </select>

           </div>


           <div>

            <table class="table table-striped">
                <th> Día </th>
                <th> Fecha </th>
                <th v-for="(instructor,index) in bookedInstructors" :key="index">{{instructor.mnemonic}}</th>
            </table>
               {{overallProgramming}}
           </div>
       </div>


    </div>

</template>

<script>



import bookingApi from "../services/booking";


// import moment from "moment";
// import { Remarkable } from 'remarkable'


import PacmanLoader from 'vue-spinner/src/PacmanLoader.vue'



export default {
  components: {  PacmanLoader },

data() {
    return {

        loadingSpinner : false,
        overallProgramming: [],
        bookedInstructors: [],
        params: {
            year: 2021,
        }
    }
},



computed: {





},





    async mounted(){

        this.loadingSpinner =true
        this.getOverallProgramming()


    },
    methods: {

        async getOverallProgramming() {
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

        async onChangeYear() {
            await this.getOverallProgramming()
        }





    }
}
</script>

<style scoped>
.ui-datepicker-calendar {
    display: none;
    }

</style>

