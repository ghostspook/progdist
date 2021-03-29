<template>
    <div>
        <new-booking></new-booking>
        <vue-good-table
            mode="remote"
            @on-sort-change="onSortChange"
            @on-column-filter="onColumnFilter"
            @on-per-page-change="onPerPageChange"
            :pagination-options="{
                enabled: true,
            }"
            :columns="columns"
            :rows="rows"
            :totalRows="totalRecords"

        >

        </vue-good-table>
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
                            filterable: true,
                            filterOptions: {
                                enabled: true,

                            },

                    },
                          {
                            label: 'Program',
                            field: 'program_id',
                            sortable: true,
                            filterable: true,
                            filterOptions: {
                                enabled: true,

                            },

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

            console.log(this.serverParams)
        },

        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);

        },

        async onPageChange(params) {
            this.updateParams({page: params.currentPage});
            await this.fetchBookings();
        },

        async onPerPageChange(params) {
            this.updateParams({rowsPerPage: params.currentPerPage});
            await this.fetchBookings();
        },

        async onSortChange(params) {

           this.updateParams({
                sort: [{
                type: params[0].type,
                field: params[0].field,
             }],
            });

            await this.fetchBookings();
        },

        async onColumnFilter(params) {
            this.updateParams(params);
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
