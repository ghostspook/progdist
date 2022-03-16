<template>
    <div>

        <div class="row">
            <div class="col-md-2">
                <button v-if="canCreateAndEditBookings" class="btn btn-success mt-2 mb-3" @click="newSession()">
                    <i class="fa fa-plus"></i>
                    Nueva Sesión
                </button>
            </div>

        </div>
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


                    <!-- Row Actions Slot -->
                    <template #rowActions="slotRowActionsProps" v-if="canCreateAndEditBookings">
                        <div class="d-flex flex-row" >
                            <a   class="edit btn btn-sm btn-primary"  @click="onRowEdit(slotRowActionsProps.rowId)"><i class="fa fa-edit"></i></a>
                            <a   class="ml-1 edit btn btn-sm btn-danger"  @click="onDeleteClick(slotRowActionsProps.rowId)"><i class="fa fa-trash"></i></a>
                        </div>
                    </template>

                    <!-- Global Actions Slot -->
                    <template #globalActions="slotGlobalActionsProps" v-if="canCreateAndEditBookings">
                        <div class="mt-2 mb-2 ml-2 d-flex flex-row" >

                            <a   class="edit btn btn-sm btn-primary"  @click="onRowEdit(slotGlobalActionsProps.rows)"><i class="fa fa-edit"></i></a>
                            <a   class="ml-1 edit btn btn-sm btn-danger"  @click="onDeleteClick(slotGlobalActionsProps.rows)"><i class="fa fa-trash"></i></a>
                        </div>
                    </template>


                </fancy-table>

            </div>

            <!-- Delete Modal -->
            <modal name="deleteConfirmation" height="auto"  width="30%">
                <div class="card">
                    <div class="card-header p-3 mb-2">
                        <div class="row">
                            <h4 class="ml-2">
                                ¿Está seguro que desea eliminar esta(s) sesión(es)?
                            </h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex flex-row pull-right">
                                <button class="btn btn-default" @click="doNotDelete">Cancelar</button>
                                <button class="ml-1 btn btn-danger" @click="doDelete">Eliminar</button>
                        </div>
                    </div>
                </div>
            </modal>

            <!-- Add Booking Modal  -->
            <modal  :scrollable="true" name="addBooking" height="auto"  width="75%" :clickToClose="false">
                <add-booking
                    @add-booking-close="onAddBookingClose"
                    @add-booking-save="onAddBookingSave"
                    :booking-id="bookingIdToEdit"

                />
            </modal>
        </div>
    </div>

</template>

<script>
import AddBooking from './AddBooking.vue'
import FancyTable from './FancyTable.vue'

import bookingsApi from '../services/booking'
import moment from "moment";
import userApi from '../services/user'


export default {
    components: {
        AddBooking,
        FancyTable,

    },
    computed: {
        canCreateAndEditBookings() {
            console.log("Computed edit", this.user)
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



                // {
                //     label: 'Acción',
                //     field: 'actions',
                //     sortable: false,
                //     filterable: false,
                //     filterOptions: {
                //         enabled: false,
                //     },
                // },
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


            user: null,
            bookingIdToDelete: [],
            bookingIdToEdit : [],



        }
    },



    methods: {

         async getUserInfo (){
              if (!this.user) {
                this.user =  await userApi.getMyUser()
                console.log("get user info", this.user)
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
            Array.isArray(row) ? this.bookingIdToEdit = row : this.bookingIdToEdit.push(row)

            if (this.bookingIdToEdit.length>0) {
                this.$modal.show('addBooking')
            }

           // this.$refs.bk.onEdit(row)
        },

        async onRowDelete(){



            for (var i=0; i< this.bookingIdToDelete.length;i++) {
                await bookingsApi.delete(this.bookingIdToDelete[i])
            }

            this.$modal.hide('deleteConfirmation')
            await this.fetchBookings();
            this.bookingIdToDelete = []
            this.toggleClearSelectedRows()
        },

        doNotDelete (){
            this.$modal.hide('deleteConfirmation')
        },

        doDelete(){
            this.onRowDelete()
        },

        onDeleteClick(row){

            Array.isArray(row) ? this.bookingIdToDelete = row : this.bookingIdToDelete.push(row)
            this.$modal.show('deleteConfirmation')
        },

        newSession(){
            this.$modal.show("addBooking")

        },

        onAddBookingClose() {
            console.log("Closing")
            this.bookingIdToEdit= []
            this.$modal.hide("addBooking")
            this.toggleClearSelectedRows()
        },

        async onAddBookingSave (){
            this.$modal.hide("addBooking")
            this.bookingIdToEdit= []
            await this.fetchBookings()
            this.toggleClearSelectedRows()

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
        console.log("Permisos", this.user)
        this.serverParams.fromBookingDate = moment().startOf('isoWeek').toDate().toISOString().substr(0,10)
        this.serverParams.toBookingDate = moment('2022-08-18').toDate()//moment().endOf('isoWeek').toDate().toISOString().substr(0,10)
        await this.fetchBookings()
        //console.log(this.rows)


    }


}
</script>

<style>

</style>

