<template>
    <div class="card">
        <h5 class="card-header">
            Editar las sesiones seleccionadas
        </h5>
        <div class="card-body">
            <div class="form-group">
                <label for="virtualRoom">Aula Virtual</label>
                    <v-select
                        :options="virtualrooms"
                        label="mnemonic"
                        v-model="selectedVirtualRoom"

                />
            </div>
                <div class="form-group">
                    <label for="newLink">URL</label>
                    <input v-model="newLink" class="form-control" name="newLink" id="newLink" type="url" required />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input v-model="password" class="form-control" name="password" id="text" />
                </div>
                <div class="form-group">
                    <label for="waiting_room">Sala de Espera</label>
                    <select v-model="hasWaitingRoom" name="waiting_room" id="waiting_room" class="form-control">
                        <option value="0">No</option>
                        <option value="1" selected>Sí</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button
                        :disabled="addingLink"
                        class="btn btn-success"
                        @click="onAddMeetingLinkClick"
                    >
                        Añadir Link
                    </button>
                </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>
