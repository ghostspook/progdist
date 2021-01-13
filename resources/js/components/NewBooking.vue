<template>
    <div class="ml-2 mr-2">
        <button class="btn btn-primary" @click="onNewClick">{{ newButtonText }}</button>
        <div v-if="displayForm" class="mt-1 mb-2">
            Aquí va el formulario
        </div>
    </div>
</template>

<script>
export default {
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
        }
    }
}
</script>
