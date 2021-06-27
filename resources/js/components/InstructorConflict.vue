<template>
<div class="card">
    <h5 class="card-header">
        Chequear Cruces
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <label for="bookingDateFrom">Desde</label>
                        <datepicker
                            id="bookingDateFrom"
                            v-model="bookingDateFrom"
                            :language="es"
                            :bootstrap-styling="true"
                        >
                        </datepicker>
                    </div>
                    <div class="col-md-3">
                        <label for="bookingDateTo">Hasta</label>
                        <datepicker
                            id="bookingDateTo"
                            v-model="bookingDateTo"
                            :language="es"
                            :bootstrap-styling="true"
                        >
                        </datepicker>
                    </div>
                    <div class="col-md-3">
                        <label for="instructor">Profesor</label>
                            <v-select
                                :options="selectableInstructors"
                                label="label"
                                v-model="selectedInstructor"
                                :reduce="(i) => (!i ? null : i.id)"

                        />
                    </div>
                    <div class="col-md-3 mt-4">
                        <button
                            class="btn btn-success"
                            @click="onQueryClick"
                            >
                            Consultar
                        </button>
                    </div>

                </div>
            </div>
            <div class="form-group col-md-12">
                <vue-good-table ref="instructorConflictsTable"
                    mode="remote"
                    @on-sort-change="onSortChange"
                    @on-column-filter="onColumnFilter"
                    @on-page-change="onPageChange"
                    @on-per-page-change="onPerPageChange"
                    :pagination-options="{
                        enabled: true,
                        perPageDropdown: [50],
                        dropdownAllowAll: false,
                    }"
                    :columns="columns"
                    :rows="rows"
                    :totalRows="totalRecords"

                >
                    <!-- <div slot="table-actions">
                        <download-excel
                            class="btn btn-default"
                            :fetch="fetchAllBookings"
                            :fields="json_fields"
                            worksheet="Sesiones"
                            name="sesiones.xls"
                        >
                            Exportar a Excel
                        </download-excel>
                    </div> -->
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field == 'overlap'">
                            <!-- <a class="edit btn btn-sm btn-primary"  @click="onRowEdit(props.row.booking_id)"><i class="fa fa-edit"></i></a> -->
                            <!-- <a class="edit btn btn-sm btn-danger"  @click="onRowDelete(props.row.booking_id)"><i class="fa fa-trash"></i></a> -->
                             <div v-if="props.row.overlap == 1" class="alert alert-danger" role="alert">
                                    ¡Posible Conflicto!
                             </div>
                        </span>

                      <!-- <span v-else>
                        {{props.formattedRow[props.column.field]}}
                        </span> -->
                    </template>
                </vue-good-table>
            </div>
        </div>




    </div>
    </div>
</template>

<script>

import Datepicker from "vuejs-datepicker";
import moment from "moment";
import { en, es } from "vuejs-datepicker/dist/locale";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'

import instructorsApi from "../services/instructor";
import bookingsApi from '../services/booking'




export default {
    components: {
        vSelect,
        Datepicker,
        VueGoodTable,

    },
     props: {

    },
    data() {
        return {
            es: es,
            bookingDateFrom: null,
            bookingDateTo: null,
            instructors: [],
            selectedInstructor: null,

            columns: [
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
                            filterable: false,
                            filterOptions: {
                                enabled: false,
                            },
                        },
                        {
                            label: 'Programa',
                            field: 'program',
                            sortable: false,
                            filterable: true,
                            filterOptions: {
                                enabled: true,
                            },
                        },
                        // {
                        //     label: 'Área',
                        //     field: 'area',
                        //     sortable: false,
                        //     filterable: false,
                        //     filterOptions: {
                        //         enabled: false,
                        //     },
                        // },

                        {
                            label: 'Profesor',
                            field: 'instructor',
                            sortable: false,
                            filterable: false,
                            filterOptions: {
                                enabled: false,
                            },
                        },

                        {
                            label: 'Inicia',
                            field: 'start_time',
                            formatFn: this.formatBookingTime,
                            sortable: false,
                            filterable: false,
                            filterOptions: {
                                enabled: false,
                            },
                        },

                        {
                            label: 'Termina',
                            field: 'end_time',
                            formatFn: this.formatBookingTime,
                            sortable: false,
                            filterable: false,
                            filterOptions: {
                                enabled: false,
                            },
                        },

                        {
                            label: 'Conflicto',
                            field: 'overlap',
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
                rowsPerPage: 50,

            }
        };
    },
    computed: {


        selectableInstructors(){

            var instructors = []

            this.instructors.forEach((i) => {
                                    instructors.push({
                                        id: i.id,
                                        name: i.name,
                                        mnemonic: i.mnemonic,
                                        label: i.mnemonic  + " - " + i.name,
                                    });

                                 });


            return instructors
        },
    },
    async mounted() {
        await this.fetchInstructorAreas()

     },
    methods: {
        async fetchInstructorAreas() {
            try {
                this.instructors = await instructorsApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de profesores"
                });
            }
        },

        async fetchBookings() {

            let data = await bookingsApi.getInstructorConflicts(this.serverParams,
                                                                        this.bookingDateFrom.format('YYYY-MM-DD'),
                                                                        this.bookingDateTo.format('YYYY-MM-DD'),
                                                                        this.selectedInstructor)

            this.rows = data.data
            this.totalRecords = data.total
            this.serverParams.page = 1


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

        async onQueryClick() {
          //  this.serverParams.total = 0
           // this.serverParams.page = 1
           // this.serverParams.totalRecords = 0
            this.fetchBookings();

            console.log(this.bookingDateFrom, this.bookingDateTo, this.serverParams)
            console.log(this.start_time)

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


    }

}
</script>
