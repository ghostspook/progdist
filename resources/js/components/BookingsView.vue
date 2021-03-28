<template>
    <div>
        <new-booking></new-booking>
        <vue-good-table
            mode="remote"
            @on-sort-change="onSortChange"
            :pagination-options="{
                enabled: true,
            }"
            :columns="columns"
            :rows="rows"
            :totalRows="totalRecords"
        />
    </div>
</template>

<script>
import NewBooking from './NewBooking.vue'
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'
import bookingsApi from '../services/booking'
import moment from "moment";

export default {
    components: {
        NewBooking,
        VueGoodTable
    },
    data() {
        return {
                    columns: [
                // {
                //     label: 'Día',
                //     field: 'booking_date',
                //     formatFn: this.formatBookingDay,
                //     sortable: true,


                // },

                // {
                //     label: 'Fecha',
                //     field: 'booking_date',
                //     formatFn: this.formatBookingDate,
                //     sortable: true,

                // },

                       {
                    label: 'Fecha',
                    field: 'booking_date',

                    sortable: true,

                },
            ],
            rows: [],
          //  page: 1,
           // rowsPerPage: 25,
            totalRecords: 0,

            serverParams: {
                columnFilters: {

                },

                sort: [
                            {
                            field: '', // example: 'name'
                            type: '' // 'asc' or 'desc'
                            }
                        ],

                page: 1,
                rowsPerPage: 25,

            },


        }
    },
    methods: {


        async fetchBookings() {
            //let data = await bookingsApi.getPage(this.page, this.rowsPerPage)
            let data = await bookingsApi.getPage(this.serverParams)

            this.rows = data.data
            this.totalRecords = data.total
        },

        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);
        },

       async onSortChange(params) {

           this.updateParams({
                sort: [{
                type: params[0].type,
                field: params[0].field,
             }],
            });
            console.log(this.serverParams)
            await this.fetchBookings();
        },

        formatBookingDay(value){
            moment.locale("es");
            return moment(value).format("dddd")
        },

        formatBookingDate(value){
            moment.locale("es");
            return moment(value).format("DD-MMM-yyyy")
        },

    },
    async mounted() {
        await this.fetchBookings()
    }


}
</script>
