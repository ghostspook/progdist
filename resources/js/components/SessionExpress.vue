<template>
<div class="row">
    <div class="col-md-12">
        <fancy-table
            :columns="columns"
            :rows="rows"
            :totalRows="totalRecords"
            :pagination-options="{
                enabled: true,
                perPageDropdown: [10, 25, 50],
                dropdownAllowAll: false,
            }"
            :selectable="true"
            id-field="booking_id" 
            :clear-selected-rows="clearSelectedRows"
            @on-per-page-change="onPerPageChange"
            @on-page-change="onPageChange"
            @on-column-filter="onColumnFilter"
            @on-sort-change="onSortChange"


        >


            
            <template #tableRow="slotActionsProps" v-if="canCreateAndEditBookings">
                <div class="d-flex flex-row" >
                    <a   class="edit btn btn-sm btn-primary"  @click="onRowEdit(slotActionsProps.rowId)"><i class="fa fa-edit"></i></a>
                    <a   class="edit btn btn-sm btn-danger"  @click="onDeleteClick(slotActionsProps.rowId)"><i class="fa fa-trash"></i></a>
                </div>
            </template>

        </fancy-table>

    </div>


    <modal name="deleteConfirmation" height="auto"  width="70%">
        <div class="card">
            <div class="card-header p-3 mb-2">
                <div class="row">
                    <p>
                        ¿Está seguro que desea eliminar esta sesión?
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <button class="btn btn-default pull-right" @click="doNotDelete">Cancelar</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger pull-right" @click="doDelete">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>      

            
        
    </modal>
</div>

</template>

<script>
import FancyTable from './FancyTable.vue'

import bookingsApi from '../services/booking'
import moment from "moment";
import userApi from '../services/user'


export default {
    components: {
        FancyTable
    },
    computed: {
        canCreateAndEditBookings() {
            return (!this.user) ? false : this.user.authorized_account.can_create_and_edit_bookings == 1
        },

  

                    
            
            
         
     
    },
    data() {
        return {
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
                    formatFn: 'formatBookingDay',
                    sortable: false,
                    filterable: false,
                    hidden: false,
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
                    label: 'Fecha',
                    field: 'booking_date',
                    formatFn: 'formatBookingDate',
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
                 //   formatFn: this.formatBookingTime,
                    formatFn: 'formatBookingTime',
                    sortable: true,
                    filterable: true,
                    filterOptions: {
                        enabled: true,
                    },
                },

                {
                    label: 'Termina',
                    field: 'end_time',
                    formatFn: 'formatBookingTime',
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
            clearSelectedRows: false,

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

            bookingIdToDelete: 0,

        }
    },
    
    
    
    methods: {
        async getUserInfo (){
              if (!this.user) {
                this.user = await userApi.getMyUser()
            }

        },

        toggleClearSelectedRows(){
            this.clearSelectedRows = !this.clearSelectedRows
        },

        async onPerPageChange(params){
            console.log("Per page Changed", params)
            this.updateParams({rowsPerPage: params.currentPerPage, page: params.currentPage});
            await this.fetchBookings();
            

        },

        async onPageChange (params) {
            
            this.updateParams({page: params.currentPage});
            await this.fetchBookings();
        },

        async onColumnFilter(params) {
            params.page= params.currentPage
            params.rowsPerPage = params.currentPerPage
            this.updateParams(params);
            await this.fetchBookings();
        },
        async onSortChange(params){
            this.updateParams(params)
            await this.fetchBookings();

        },

        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);

        },

        async fetchBookings() {

            let data = await bookingsApi.getPage(this.serverParams)

            this.rows = data.data
            this.totalRecords = data.total

            //console.log(this.serverParams)

            //format Bookings
            
            this.rows.forEach(b => {
                b.day =  this.formatBookingDay(b.booking_date)
                
            });


            
        },

        onRowEdit(row){
           
            console.log(row)
           // this.$refs.bk.onEdit(row)
        },

        async onRowDelete(){
            await bookingsApi.delete(this.bookingIdToDelete)
            this.$modal.hide('deleteConfirmation')
            await this.fetchBookings();
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


            
        await this.getUserInfo()
        console.log("Permisos", this.canCreateAndEditBookings)
        this.serverParams.fromBookingDate = moment().startOf('isoWeek').toDate().toISOString().substr(0,10)
        this.serverParams.toBookingDate = moment().endOf('isoWeek').toDate().toISOString().substr(0,10)
        await this.fetchBookings()
        //console.log(this.rows)

       
    }


}
</script>

<style>

</style>

