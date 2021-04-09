<template>
    <div>
        <h4 v-if="fetching">Cargando...</h4>
        <div v-if="booking">
            <h4 v-if="!editProgram" class="text-primary title" @click="onProgramNameClick">
                {{ (!booking.program) ? '-' : booking.program.name }}
            </h4>
            <v-select
                v-show="editProgram"
                id="programs"
                :options="sortedPrograms"
                @input="onProgramChange"
                label="mnemonic"
                v-model="booking.program_id"
                :reduce="(sp) => (!sp ? null : sp.id)"
            />
            <div v-if="!editDate" @click="onDateClick">
                <font-awesome-icon icon="calendar-day"/>
                {{ booking.booking_date | toLocalDateString }}
            </div>
            <datepicker
                v-if="editDate"
                v-model="booking.booking_date"
                :language="es"
                :bootstrap-styling="true"
                @closed="onDateCalendarClosed"
            />
        </div>
    </div>
</template>

<script>
import bookingsApi from '../services/booking'
import moment from 'moment'
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import Datepicker from "vuejs-datepicker";
import { en, es } from "vuejs-datepicker/dist/locale";

export default {
    components: {
        vSelect,
        Datepicker
    },
    props: {
        bookingId: {
            type: Number,
            required: true
        },
        programs: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            fetching: false,
            booking: null,
            editProgram: false,
            editDate: false,
            es: es,
        }
    },
    filters: {
        toLocalDateString(value) {
            return moment(value).toDate().toLocaleDateString()
        },
    },
    computed: {
        sortedPrograms() {
            return this.programs.sort((a, b) => a.mnemonic > b.mnemonic);
        },
    },
    async mounted() {
        await this.fetchBooking()
    },
    methods: {
        async fetchBooking() {
            this.fetching = true
            this.booking = null
            try {
                this.booking = await bookingsApi.get(this.bookingId)
            } catch (e) {
                console.log(e)
            } finally {
                this.fetching = false
            }
        },
        onProgramNameClick() {
            this.resetEditSelection()
            this.editProgram = true
        },
        onProgramChange(programId) {
            let program = this.programs.filter(p => p.id == programId)
            if (program.length == 0) {
                this.booking.program = null
            } else {
                this.booking.program = program[0]
            }
            this.resetEditSelection()
        },
        onDateClick() {
            this.resetEditSelection()
            this.editDate = true
        },
        onDateCalendarClosed(value) {
            //this.booking.booking_date = value
            this.resetEditSelection()
        },
        resetEditSelection() {
            this.editProgram = false
            this.editDate = false
        }
    }
}
</script>
