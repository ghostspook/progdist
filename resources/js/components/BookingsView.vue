<template>
    <div>
        <div>
            <new-booking :user="user" ref='bk' @booking-save="onBookingSave"></new-booking>
        </div>
        <div class="row mt-4 mb-2 d-flex justify-content-center">


                <label for="Desde" class="col-md-1 col-sm-2 col-form-label ml-2 text-right">Desde</label>
                <input id="fromBookingDate" v-model="serverParams.fromBookingDate" type="date" class="form-control col-md-2 ml-2"/>


                <label for="Hasta" class="col-md-1 col-sm-2 col-form-label ml-2 text-right">Hasta</label>
                <input  v-model="serverParams.toBookingDate"  id="toBookingDate" name="toBookingDate" type="date" class="form-control col-md-2 ml-2"/>

            <button  class="col-md-1 ml-4  btn btn-success" @click="onQueryBookingsClick" >
                    Consultar
            </button>


        </div>

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
            <div slot="table-actions">
                <download-excel
                    class="btn btn-default"
                    :fetch="exportBookings"
                    :fields="json_fields"
                    worksheet="Sesiones"
                    name="sesiones.xls"
                >
                   <i class="fa fa-file-excel-o">  Exportar</i>
                </download-excel>
            </div>
             <template slot="table-row" slot-scope="props">
                 <span v-if="props.column.field == 'actions'">
                    <a v-if="canCreateAndEditBookings"  class="edit btn btn-sm btn-primary"  @click="onRowEdit(props.row.booking_id)"><i class="fa fa-edit"></i></a>
                    <a v-if="canCreateAndEditBookings"  class="edit btn btn-sm btn-danger"  @click="onDeleteClick(props.row.booking_id)"><i class="fa fa-trash"></i></a>
                </span>

                <!-- <span v-else>
                {{props.formattedRow[props.column.field]}}
                </span> -->
            </template>
        </vue-good-table>

        <modal name="deleteConfirmation" height="auto">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="col-md-12">
                            <p>
                                ¿Está seguro que desea eliminar esta sesión?
                            </p>
                            <div>
                                <button class="btn btn-default pull-right" @click="doNotDelete">Cancelar</button>
                                <button class="btn btn-danger pull-right" @click="doDelete">Eliminar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import NewBooking from './NewBooking.vue'
import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'
import bookingsApi from '../services/booking'
import moment from "moment";
import { Remarkable } from 'remarkable'

import userApi from '../services/user'



export default {
    components: {
        NewBooking,
        VueGoodTable
    },


    data() {
        return {
                user: null,


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
                        field: 'support',
                        html: 'true',
                        sortable: false,
                        filterable: false,
                        filterOptions: {
                            enabled: false,
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

            page: 1,
            rowsPerPage: 50,
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
                fromBookingDate: null,
                toBookingDate: null,

            },

            // for Export to Excel component
            excelData: [],

            json_fields: {
                'Día': {
                    field: "booking_date",
                    callback: (value) => {
                        return this.formatBookingDay(value)
                    }
                },
                'Fecha': {
                    field: "booking_date",
                    callback: (value) => {
                        return this.formatBookingDate(value)
                    }

                },
                'Programa': "program",
                'Área': "area",
                'Profesor': "instructor",
                'Inicia':  {
                    field: "start_time",
                    callback: (value) => {
                        return this.formatBookingTime(value)
                    }

                },
                'Termina':  {
                    field: "end_time",
                    callback: (value) => {
                        return this.formatBookingTime(value)
                    }

                },
                'Aula Física': "physical_room",
                'Aula Virtual': "virtual_room",
                'link': "link",
                'contraseña': "password",
                'soporte': "support",

            },

            bookingIdToDelete: 0,
        }
    },
    computed: {

        canCreateAndEditBookings() {
            return (!this.user) ? false : this.user.authorized_account.can_create_and_edit_bookings == 1
        },

        computedRows() {
            var md = new Remarkable();

            return this.rows.map(r => {
                r.supportHtml = md.render(r.support)
                return r
            })
        }
    },
    methods: {

        async getUserInfo (){
              if (!this.user) {
                this.user = await userApi.getMyUser()
            }

        },

        async exportBookings(){
            let data = await bookingsApi.getAll()

            return data
         //   this.excelData = data.data
        },

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
            this.$refs.bk.onEdit(row)
            console.log(row)
            this.$refs.bk.onEdit(row)
        },

        async onRowDelete(){
            await bookingsApi.delete(this.bookingIdToDelete)
            this.$modal.hide('deleteConfirmation')
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

        formatBookingTime(value){
            return moment(value).toDate().format("HH:mm")
        },

        doNotDelete (){
            this.$modal.hide('deleteConfirmation')
        },

        doDelete(){
            this.onRowDelete()
        },
        onDeleteClick(row){
            this.bookingIdToDelete = row
            this.$modal.show('deleteConfirmation')
        },


        async onBookingSave () {
            await this.fetchBookings()
        },

        async onQueryBookingsClick (){
            await this.fetchBookings()
        },

    },
    async mounted() {
        await this.getUserInfo()

        this.serverParams.fromBookingDate = moment().startOf('isoWeek').toDate().toISOString().substr(0,10)
        this.serverParams.toBookingDate = moment().endOf('isoWeek').toDate().toISOString().substr(0,10)
        await this.fetchBookings()
        //console.log(this.rows)
    }


}
</script>

