<template>
<div>

    <fancy-table
        :columns="columns"
        :rows="rows"
    >

    </fancy-table>
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

        formattedRows(){



        }
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
        }
    },
    methods: {
        async getUserInfo (){
              if (!this.user) {
                this.user = await userApi.getMyUser()
            }

        },


        async fetchBookings() {

            let data = await bookingsApi.getPage(this.serverParams)

            this.rows = data.data
            this.totalRecords = data.total

            console.log(this.serverParams)
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

        this.serverParams.fromBookingDate = moment().startOf('isoWeek').toDate().toISOString().substr(0,10)
        this.serverParams.toBookingDate = moment().endOf('isoWeek').toDate().toISOString().substr(0,10)
        await this.fetchBookings()
        //console.log(this.rows)

          this.columns.forEach(col => {
                if (col.formatFn ){
                    var mydynamic_Function_Name = col.formatFn;
                    console.log("Col FN", mydynamic_Function_Name)

                    
                    this.rows.map((row) => {

                            if (col.index == row.index) {

                            this[col.formatFn](row)
                            console.log("Row", row)
                            }
                        })

                    console.log("Ahi te va",this[mydynamic_Function_Name]('2022-03-11'))
                }

            });
    }


}
</script>

<style>

</style>

