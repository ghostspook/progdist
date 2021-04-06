<template>
    <div>
        <new-booking ref="bk"></new-booking>
        <vue-good-table
            mode="remote"
            @on-sort-change="onSortChange"
            @on-column-filter="onColumnFilter"
            @on-page-change="onPageChange"
            @on-per-page-change="onPerPageChange"
            :pagination-options="{
                enabled: true,
                perPageDropdown: [10, 25, 50],
                dropdownAllowAll: false,
            }"
            :columns="columns"
            :rows="computedRows"
            :totalRows="totalRecords"

        >
             <template slot="table-row" slot-scope="props">
                 <span v-if="props.column.field == 'actions'">
                    <a class="edit btn btn-sm btn-primary"  @click="onRowEdit(props.row.booking_id)"><i class="fa fa-edit"></i></a>
                    <a class="edit btn btn-sm btn-danger"  @click="onRowDelete(props.row.booking_id)"><i class="fa fa-trash"></i></a>
                </span>

                <!-- <span v-else>
                {{props.formattedRow[props.column.field]}}
                </span> -->
            </template>
        </vue-good-table>
    </div>
</template>

<script>
import NewBooking from './NewBooking.vue'
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'
import bookingsApi from '../services/booking'
import moment from "moment";
import { Remarkable } from 'remarkable'

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
                        label: 'booking_id',
                        field: 'booking_id',
                        sortable: false,
                        filterable: false,
                        hidden: true,
                        filterOptions: {
                            enabled: false,
                        },
                    },

                    {
                        label: 'Día',
                        field: 'booking_date',
                        formatFn: this.formatBookingDay,
                        sortable: false,
                        filterable: false,
                        filterOptions: {
                            enabled: false,
                        },
                    },
                    {
                        label: 'Fecha',
                        field: 'booking_date',
                        formatFn: this.formatBookingDate,
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },
                    {
                        label: 'Programa',
                        field: 'program',
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },
                    {
                        label: 'Área',
                        field: 'area',
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Profesor',
                        field: 'instructor',
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Inicia',
                        field: 'start_time',
                        formatFn: this.formatBookingTime,
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Termina',
                        field: 'end_time',
                        formatFn: this.formatBookingTime,
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Aula Física',
                        field: 'physical_room',
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Aula Virtual',
                        field: 'virtual_room',
                        sortable: true,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Link',
                        field: 'link',
                        sortable: false,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Password',
                        field: 'password',
                        sortable: false,
                        filterable: false,
                        filterOptions: {
                            enabled: false,
                        },
                    },

                    {
                        label: 'Soporte',
                        field: 'supportHtml',
                        html: true,
                        sortable: false,
                        filterable: true,
                        filterOptions: {
                            enabled: true,
                        },
                    },

                    {
                        label: 'Acción',
                        field: 'actions',
                        sortable: false,
                        filterable: false,
                        filterOptions: {
                            enabled: false,
                        },
                    },



                ],
            rows: [],
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
                rowsPerPage: 10,

            },


        }
    },
    computed: {
        computedRows() {
            var md = new Remarkable();

            return this.rows.map(r => {
                r.supportHtml = md.render(r.support)
                return r
            })
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

        onRowEdit(row){
            console.log(row)
            this.$refs.bk.onEdit(row)
        },

        onRowDelete(row){
            console.log(row)
        },

        formatBookingDay(value){
            moment.locale("es");
            return moment(value).format("dddd")
        },

        formatBookingDate(value){
            moment.locale("es");
            return moment(value).format("DD-MMM-yyyy")
        },

        formatBookingTime(value){
            return moment(value).toDate().format("HH:mm")
        },
    },
    async mounted() {
        await this.fetchBookings()
    }


}
</script>
