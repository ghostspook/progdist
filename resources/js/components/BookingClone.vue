<template>
    <div class="card">
        <h5 class="card-header">
            Duplicar sesión
        </h5>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="my-calendar">
                    <vue-cal
                        hide-view-selector
                        :time="false"
                        active-view="month"
                        xsmall
                        @cell-click="onCellClick"
                        :events="events"
                    >
                    </vue-cal>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueCal from 'vue-cal'
import moment from 'moment'

export default {
    components: {
        VueCal
    },
    props: {
        booking: {
            type: Object,
            required: true
        },
    },
    data () {
        return {
            events: []
        }
    },
    methods: {
        onCellClick (e) {
            console.log(e)
            this.events.push({ start: moment(e).startOf('day').toDate(), end: moment(e).endOf('day').toDate(), title: this.booking.program.mnemonic })
        }
    }
}
</script>

<style scoped>
.my-calendar {
    height: 300px;
    width: 300px;
}
</style>
