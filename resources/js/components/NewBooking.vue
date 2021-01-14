<template>
    <div class="ml-2 mr-2">
        <button class="btn btn-primary" @click="onNewClick">{{ newButtonText }}</button>
        <div v-if="displayForm" class="mt-2 mb-3">
            <form>
                <div class="row">
                        <div class="col-md-2 form-group">
                            <label for="subject">Tema</label>
                            <datepicker :format="myFormatter"></datepicker>
                        </div>
                        <div class="col-md-2">Columna 2</div>
                        <div class="col-md-2">Columna 3</div>
                        <div class="col-md-2">Columna 4</div>
                        <div class="col-md-2">Columna 5</div>
                        <div class="col-md-2">Columna 6</div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
    components: {
        Datepicker
    },
    data() {
        return {
            displayForm: false
        }
    },
    computed: {
        newButtonText() {
            return this.displayForm ? "Esconder" : "Nuevo"
        }
    },
    methods: {
        onNewClick() {
            this.displayForm = !this.displayForm
        },
        myFormatter(date) {
        return moment(date).format('d-MMM-yyyy');
        }
    }
}
</script>
