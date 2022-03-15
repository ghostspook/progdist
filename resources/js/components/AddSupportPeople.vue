<template>
    <div>
        <div class="card">
            <div class="card-header p-3 mb-2 bg-dark text-white">
                    <div class="d-flex">
                        <div class="mr-auto p-2">
                            <h5><span>Staff de Soporte seleccionado para {{programName}} </span></h5>

                        </div>
                        <div class="p-2">
                            <button class="bg-dark text-white btn btn-danger" @click="cancelSupportPeople()">Cancelar</button>
                            <button class="btn btn-success" @click="saveSupportPeople()">Guardar</button>
                        </div>
                    </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table bg-dark text-white mb-2">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Físico/Virtual</th>
                                    <th scope="col">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sp in newSelectedSupportPeople"
                                                v-bind:key="sp.support_person_id"
                                                :value="sp.support_person_id">
                                    <td>{{ sp.name }}</td>
                                    <td>{{ sp.role | supportRole }}</td>
                                    <td>{{ sp.type | supportType  }}</td>
                                    <td>
                                        <a href='#' class="edit text-danger ml-3" @click="onDeleteSupportPerson(sp.support_person_id)"><i class="fa fa-trash"></i></a>

                                    </td>


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
                                <div class="col-md-2">
                                    <label><strong>Nombre</strong></label>
                                </div>
                                <div class="col-md-10">
                                    <v-select
                                        id="supportPeople"
                                        :options="sortedSupportPeopleList"
                                        label="label"
                                        v-model="newSupportPerson"

                                    />
                                </div>
                            </div>
                            <div class="row mb-2">
                                <span :class="newSupportPersonError? 'alert alert-danger' :''">  {{ newSupportPersonError }}</span>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label><strong>Rol</strong></label>
                                </div>
                                <div class="col-md-4 mt-3 d-flex align-items-start">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="supportRole" id="academicCoord" :value="role_coord" v-model="newSupportRole">
                                        <label class="form-check-label" for="academicCoord">Coordinación Académica</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3 d-flex align-items-start">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="supportRole" id="academicSupport" :value="role_acad" v-model="newSupportRole" checked>
                                        <label class="form-check-label" for="academicSupport">Soporte Académico</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3 d-flex align-items-start">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="supportRole" id="tiSupport"  :value="role_ti" v-model="newSupportRole" >
                                        <label class="form-check-label" for="tiSupport">Soporte Técnico</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-3">
                                    <label><strong>Tipo</strong></label>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="supportType" id="physicalSupport" :value="support_type_physical" v-model="newSupportType" checked>
                                        <label class="form-check-label" for="physicalSupport">Físico</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="supportType" id="virtualSupport" :value="support_type_virtual" v-model="newSupportType">
                                        <label class="form-check-label" for="virtualSupport">Virtual</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-3 d-flex justify-content-center">
                                    <button class="btn btn-primary" @click="onAddSupportClick"> Agregar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <notifications group="notificationGroup" position="top center" />
    </div>
</template>

<script>
import supportPeopleApi from "../services/supportperson";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";



const ROLE_COORD = 1;
const ROLE_ACAD = 2;
const ROLE_TI = 3;

const SUPPORT_TYPE_PHYSICAL = 0;
const SUPPORT_TYPE_VIRTUAL = 1;

export default {
    components: {
        vSelect,
    },
    props: {
        bookingStaff: {
            type: Array,
            default() {
                return []
            }
        },
        programName: {
            type: String,
            required: true,
        },

    },

    computed: {
        sortedSupportPeopleList() {
            this.supportPeopleList.forEach(sp => {
                sp.label = sp.mnemonic + ' - ' + sp.name
            });
            return this.supportPeopleList.sort((a, b) => a.mnemonic > b.mnemonic);
        },
    },

    filters: {
            supportRole: function (value) {
                switch(value) {
                    case ROLE_COORD:
                        return 'Coordinación Académica'
                    case ROLE_ACAD:
                        return 'Soporte Académico'
                    case ROLE_TI:
                        return 'Soporte TI'
                }

            },

            supportType: function (value) {
                switch(value) {
                    case SUPPORT_TYPE_PHYSICAL:
                        return 'Físico'
                    case SUPPORT_TYPE_VIRTUAL:
                        return 'Virtual'

                }

            },

        },

    data() {
        return{
            role_coord: ROLE_COORD,
            role_acad: ROLE_ACAD,
            role_ti: ROLE_TI,
            support_type_physical: SUPPORT_TYPE_PHYSICAL,
            support_type_virtual: SUPPORT_TYPE_VIRTUAL,


            supportPeopleList: [],


            newSupportPerson: {},
            newSupportRole: ROLE_ACAD,
            newSupportType: SUPPORT_TYPE_PHYSICAL,

            newSelectedSupportPeople: [],


            newSupportPersonError:"",
        }
    },
    // beforeMount (){
    //     this.newSelectedSupportPeople = this.selectedSupportPeople
    //     console.log ("newSelectedSupportPeople" ,this.newSelectedSupportPeople  )
    // },

    async mounted(){
        await this.fetchSupportPeople()
        this.newSelectedSupportPeople = this.bookingStaff
        console.log ("newSelectedSupportPeople" ,this.newSelectedSupportPeople )


    },
    watch: {
        bookingStaff:
            async function(val) {
                console.log("val", val)
                this.newSelectedSupportPeople = this.bookingStaff
            }
    },
    methods: {
        saveSupportPeople(){
            this.$emit('update-support-people', this.newSelectedSupportPeople ,
                        this.supportStaffToString(this.newSelectedSupportPeople)
                        )
        },
        cancelSupportPeople(){
            this.$emit('cancel-support-people')
        },

        onAddSupportClick () {

            this.newSupportPersonError = ""

            if(Object.keys(this.newSupportPerson).length === 0){
                this.newSupportPersonError = "Debe escoger una persona para agregar"
                return
            }


            this.newSelectedSupportPeople.push({
                support_person_id: this.newSupportPerson.id,
                role: this.newSupportRole,
                type: this.newSupportType,
                name: this.newSupportPerson.mnemonic + ' - ' + this.newSupportPerson.name,
                role_text: this.$options.filters.supportRole(this.newSupportRole) ,
                type_text: this.$options.filters.supportType(this.newSupportType) ,
            })


        },
        onDeleteSupportPerson(p){
            console.log ("id", p)
            console.log ("antes", this.newSelectedSupportPeople)
            this.newSelectedSupportPeople = this.newSelectedSupportPeople.filter( sp => sp.support_person_id!=p)
            console.log ("después", this.newSelectedSupportPeople)
        },

        supportStaffToString (staff) {
            var supportStaff = ""
            var coordStaff= "**Coordinación Académica:** "
            var acadStaff= "**Académico:** "
            var tiStaff= "**Técnico:** "


            //Coordinación Académica
            var coordPeople = staff.filter( sp => sp.role== ROLE_COORD)

            coordPeople.forEach(p => {
                coordStaff = ' ' + coordStaff + p.name + ' / '

            });

            //Soporte Académico
            var acadPeople= staff.filter( sp => sp.role== ROLE_ACAD)

            acadPeople.forEach(p => {
                acadStaff = ' ' + acadStaff + p.name + ' / '

            });

            //Soporte Técnico
            var tiPeople= staff.filter( sp => sp.role== ROLE_TI)

            tiPeople.forEach(p => {
                tiStaff = ' ' + tiStaff + p.name + ' / '

            });

            console.log("coord People", coordPeople)
            console.log("acad People", acadPeople)
            console.log("ti People", tiPeople)

            coordPeople.length>0 ?  supportStaff = ' ' + supportStaff + '. ' + coordStaff : ''
            acadPeople.length>0 ?  supportStaff= ' ' + supportStaff + '. ' + acadStaff : ''
            tiPeople.length>0 ?  supportStaff = ' ' + supportStaff + '. ' + tiStaff : ''


            return supportStaff

        },



        async fetchSupportPeople() {
            try {
                this.supportPeopleList = await supportPeopleApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de personas de soporte"
                });
            }
        },
    }
}
</script>
