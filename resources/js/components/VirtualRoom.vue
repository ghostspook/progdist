<template>
    <div>
        <div class="row">
                <div class="col-md-6 ml-4 mt-4">
                    <h2> Buscar disponibilidad de Aula Virtual </h2>
                </div>
        </div>
        <div class="ml-2 mt-4 mr-1" >
            <div class="row">

                <div class="col-md-2 mt-4">
                    Desde:
                    <input type="Date" v-model="startDate" @change="onStartDateChange"/>
                </div>
                <div class="col-md-2 mt-4">
                    Hasta:
                    <input type="Date" v-model="endDate" @change="onEndDateChange"/>
                </div>

                <!-- <div class="col-md-6">
                    Ordenar por:
                    <multiselect
                        id="calendarOrdering"
                        v-model="selectedOrdering"
                        :options="selectableOrdering"
                        @input="onOrderingChange"
                        track-by="orderBy"
                        label="orderBy"
                        :multiple="true"
                        :taggable="true"
                        :showLabels="true"
                        :hide-selected="true"
                    ></multiselect>
                </div> -->
            </div>
        </div>

        <div class="row mt-4" >
            <div class="col-md-12 ml-2" >
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
                        :rows="rows"
                        :totalRows="totalRecords"

                    >
                </vue-good-table>
            </div>
        </div>

    </div>
</template>

<script>

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'

import bookingsApi from '../services/booking'

import moment from 'moment'





export default {
    components: {
        Multiselect,
        VueGoodTable,

    },
    data() {
        return {

            startDate: moment().format("YYYY-MM-DD"),
            endDate: moment().format("YYYY-MM-DD"),

            columns: [
                        {
                            label: 'booking_id',
                            field: 'id',
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
                                enabled: false,
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
                            label: 'Aula Virtual',
                            field: 'virtual_room',
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

        }
    },
    computed: {



    },
    async mounted() {
        await this.fetchBookings()


    },
    methods: {


        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);

        },

        async onPageChange(params) {
            this.updateParams({page: params.currentPage});
            await this.fetchBookings()
        },

        async onPerPageChange(params) {
            this.updateParams({rowsPerPage: params.currentPerPage});
            await this.fetchBookings()
        },

        async onSortChange(params) {

           this.updateParams({
                sort: [{
                type: params[0].type,
                field: params[0].field,
             }],
            });

            await this.fetchBookings()

        },

        async onColumnFilter(params) {
            this.updateParams(params);
            await this.fetchBookings()

        },


        async fetchBookings() {


            this.serverParams.from = moment(this.startDate).toDate().format('YYYY-MM-DD')
            this.serverParams.to = moment(this.endDate).toDate().format('YYYY-MM-DD')

            let data = await bookingsApi.getPage(this.serverParams)

            console.log("Data",data)

            this.rows = data.data
            this.totalRecords = data.total



        },



        async  onStartDateChange(){
            await this.fetchBookings()
        },

        async  onEndDateChange(){
            await this.fetchBookings()
        },

        formatBookingTime(value){
            return moment(value).toDate().format("HH:mm")
        },


        async onOrderingChange (){
            await this.fetchBookings()
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



