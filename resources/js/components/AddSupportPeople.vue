<template>
    <div>
        <div class="card">
            <div class="card-header p-3 mb-2 bg-dark text-white">
                <div class="row">
                    <div class="col-md-9 float-left">
                        <h5>Staff de Soporte seleccionado</h5>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button class="bg-dark text-white btn btn-danger mb-1" @click="cancelSupportPeople()">Cancelar</button>
                    </div>
                    <div class="col-md-1 ml-1 pull-right">
                        <button class="btn btn-success" @click="saveSupportPeople()">Guardar</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Físico/Virtual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> KSM - Karina San Martín</td>
                                    <td>Coordinación Académica</td>
                                    <td>Virtual</td>
                                    <td><button class="btn btn-danger"> <i class="fas fa-user-times"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <div class="card-header p-3 mb-2 bg-secondary text-white">
                            <h5>Agregar Soporte</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label><strong>Nombre</strong></label>
                                </div>
                                <div class="col-md-1">
                                    <select>
                                        <option>MA - Maritza Allauca</option>
                                        <option>MNJ - María José Naranjo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label><strong>Rol</strong></label>
                                </div>
                                <div class="col-md-7 mt-3 d-flex align-items-start">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Coordinación Académica</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                        <label class="form-check-label" for="inlineRadio2">Soporte Académico</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
                                        <label class="form-check-label" for="inlineRadio3">Soporte Técnico</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label><strong>Tipo</strong></label>
                                </div>
                                <div class="col-md-1 mt-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Físico</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                        <label class="form-check-label" for="inlineRadio2">Virtual</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary"> Agregar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
    },
    data() {
        return{
        }
    },

    methods: {
        saveSupportPeople(){
            this.$emit('update-support-people', this.selectedLink)
        },
        cancelSupportPeople(){
            this.$emit('cancel-support-people')
        },
    }
}
</script>
