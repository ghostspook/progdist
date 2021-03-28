<template>
    <div>
        <new-booking></new-booking>
        <vue-good-table
            mode="remote"
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

export default {
    components: {
        NewBooking,
        VueGoodTable
    },
    data() {
        return {
            rows: [],
            page: 1,
            rowsPerPage: 25,
            totalRecords: 0,
            columns: [

            ]
        }
    },
    methods: {
        async fetchBookings() {
            let data = await bookingsApi.getPage(this.page, this.rowsPerPage)
            this.rows = data.data
            this.totalRecords = data.total
        }
    },
    async mounted() {
        await this.fetchBookings()
    }
}
</script>
