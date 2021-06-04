<template>
    <div class="cloning-dates-list">
        <h5 class="title mt-2 ml-1">
            <font-awesome-icon  icon="calendar-day"/> Fechas Seleccionadas
        </h5>
        <ul>
            <li class="mt-2" v-for="(date, index) in sortedCloningDates" :key='index'>
                {{ date.start  | toLocalDateString }}
                <a href='#' class="edit text-danger ml-3" @click="onDeleteCloningDate(date.start)"><i class="fa fa-trash"></i></a>
            </li>
        </ul>

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

    },
    watch: {
        cloningDates:
            function(val) {
                console.log("Cloning Dates", this.cloningDates)

            }
    },
    methods: {

        onDeleteCloningDate(dateToDelete){
            this.$emit('cloning-date-delete', {
                    cloningDate: dateToDelete,
                })

        },



    }
}
</script>


<style scoped>
div.cloning-dates-list {
    height: 400px;
    overflow: scroll;
}

div.cloning-dates-list ul {
    list-style-type: none;
}
</style>
