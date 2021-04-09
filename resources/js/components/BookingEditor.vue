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
        </div>
    </div>
</template>

<script>
import bookingsApi from '../services/booking'
import moment from 'moment'
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect
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
        }
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
            this.editProgram = true
        },
        onProgramChange(programId) {
            let program = this.programs.filter(p => p.id == programId)
            if (program.length == 0) {
                this.booking.program = null
            } else {
                this.booking.program = program[0]
            }
            this.editProgram = false
        }
    }
}
</script>
