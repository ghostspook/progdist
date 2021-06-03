<template>
    <div class="cloning-dates-list">
        <font-awesome-icon  icon="calendar-day"/> Fechas Seleccionadas

            <div class="mt-2" v-for="(date, index) in sortedCloningDates">
                {{ date.start  | toLocalDateString }}
                <a class="edit btn btn-sm btn-danger ml-3" @click="onDeleteCloningDate(date.start)"><i class="fa fa-trash"></i></a>


            </div>

    </div>
</template>

<script>
import moment from 'moment'

export default {
    components: {

    },
    props: {
        cloningDates: {
            type: Array,
            required: true
        },
    },
    data () {
        return {
            events: [],

        }
    },
    filters: {
        toLocalDateString(value) {
            return moment(value).toDate().format('DD-MMM-YYYY')
        },
    },
    computed: {
        sortedCloningDates() {
        return this.cloningDates.sort((a, b) => a.start > b.start);
        },

    },

    mounted() {
        console.log("Cloning Dates", this.cloningDates)
    },
    watch: {
        cloningDates:
            function(val) {
                        console.log("Cloning Dates", this.cloningDates)

            }
    },
    methods: {

        onDeleteCloningDate(dateToDelete){
            console.log("Fecha a Quitarse", dateToDelete)
            this.$emit('cloning-date-delete', {
                    cloningDate: dateToDelete,
                })

        },



    }
}
</script>


<style scoped>
div.cloning-dates-list {
  background-color: lightblue;
   width: 200px;
  height: 400px;
  overflow: scroll;
}
</style>
