<template>
    <div class="card">
        <h5 class="card-header">
            Staff de Soporte
        </h5>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="instructorName">Nombre</label>
                            <input type="text" size="15" v-model="supportPersonName">
                        </div>
                        <div class="col-md-2">
                            <label for="instructorMnemonic">Mnemónico</label>
                            <input type="text" size="8" v-model="supportPersonMnemonic">
                        </div>

                        <div class="col-md-2">
                            <button
                                v-if="canCreateAndEditBookings"
                                :disabled="adding"
                                class="btn btn-success"
                                @click="onAddSupportPersonClick"
                                >
                                Añadir
                            </button>
                        </div>

                    </div>
                </div>
                <div class="form-group col-md-12">
                    <vue-good-table
                        mode="remote"
                        @on-sort-change="onSortChange"
                        @on-column-filter="onColumnFilter"
                        @on-page-change="onPageChange"
                        @on-per-page-change="onPerPageChange"
                        :pagination-options="{
                            enabled: true,
                            perPageDropdown: [10, 25, 50],
                            dropdownAllowAll: false,
                        }"
                        :columns="columns"
                        :rows="rows"
                        :totalRows="totalRecords"

                    >
                        <!-- <div slot="table-actions">
                            <download-excel
                                class="btn btn-default"
                                :fetch="fetchAllBookings"
                                :fields="json_fields"
                                worksheet="Sesiones"
                                name="sesiones.xls"
                            >
                                Exportar a Excel
                            </download-excel>
                        </div> -->
                        <template slot="table-row" slot-scope="props">

                                <div v-if="props.column.field == 'actions'" class="row">

                                        <a v-if="canCreateAndEditBookings" class="edit btn btn-sm btn-primary col-md-4 ml-2"  :href="'supportpeople/' + props.row.id + '/edit'"   ><i class="fa fa-edit"></i></a>
                                        <a v-if="canCreateAndEditBookings" class="btn btn-sm btn-danger ml-2 col-md-4 ml-2" @click="onDeleteSupportPeopleClick(props.row.id)" ><i class="fa fa-trash"></i></a>

                                </div>

                            <span v-else>
                            {{props.formattedRow[props.column.field]}}
                            </span>

                        </template>
                    </vue-good-table>
                </div>
            </div>
        </div>
        <!-- <notifications group="notificationGroup" position="top center" /> -->
         <modal name="deleteConfirmation" height="auto">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="col-md-12">
                            <p>
                                ¿Está seguro que desea eliminar a este miembro del Staff de soporte?
                            </p>
                            <div>
                                <button class="btn btn-default pull-right" @click="doNotDelete">Cancelar</button>
                                <button class="btn btn-danger pull-right" @click="doDelete">Eliminar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </modal>
    </div>
</template>

<script>



import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'



import userApi from '../services/user'
import supportPersonApi from '../services/supportperson'





export default {
    components: {

        VueGoodTable,

    },




    data() {
        return {

            user: null,
            canCreateAndEdit: false,

            supportPersonName: "",
            supportPersonMnemonic: "",

            supportPersonId: null,

            adding: false,

            columns: [
                        {
                            label: 'support_person_id',
                            field: 'id',
                            sortable: false,
                            filterable: false,
                            hidden: true,
                            filterOptions: {
                                enabled: false,
                            },
                        },


                        {
                            label: 'Nombre',
                            field: 'name',
                            sortable: false,
                            filterable: true,
                            filterOptions: {
                                enabled: true,
                            },
                        },
                        {
                            label: 'Mnemónico',
                            field: 'mnemonic',
                            sortable: false,
                            filterable: true,
                            filterOptions: {
                                enabled: true,
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
                rowsPerPage: 25,

            }
        };
    },

    computed: {


        canCreateAndEditBookings() {
            return (!this.user) ? false : this.user.authorized_account.can_create_and_edit_bookings == 1
        },


        selectableAreas(){

            var selAreas = []

            this.areas.forEach((a) => {
                                    selAreas.push({
                                        id: a.id,
                                        name: a.name,
                                        mnemonic: a.mnemonic,
                                        label: a.mnemonic  + " - " + a.name,
                                    });

                                 });


            return selAreas
        },
    },
    async mounted() {
       await this.getUserInfo()
       await this.fetchSupportPeople()


     },

    methods: {

        async getUserInfo (){
              if (!this.user) {
                this.user = await userApi.getMyUser()
            }

        },
        async fetchSupportPeople() {
            try {
                let data  = await supportPersonApi.getAllPaged(this.serverParams);
                this.rows = data.data
                this.totalRecords = data.total
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de Staff de Soporte"
                });
            }
        },



        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);

        },

        async onPageChange(params) {
            this.updateParams({page: params.currentPage});
            await this.fetchSupportPeople()
        },

        async onPerPageChange(params) {
            this.updateParams({rowsPerPage: params.currentPerPage});
            await this.fetchSupportPeople()
        },

        async onSortChange(params) {

           this.updateParams({
                sort: [{
                type: params[0].type,
                field: params[0].field,
             }],
            });

            await this.fetchSupportPeople()

        },

        async onColumnFilter(params) {
            this.updateParams(params);
            await this.fetchSupportPeople()

        },

        async onAddSupportPersonClick() {
            this.adding = true

            if ( this.supportPersonName == "" || this.supportPersonMnemonic == ""){
                alert("Debe ingresar el nombre y mnemónico del miembro del staff de soporte")
                this.adding = false
                return
            }


            var supportPersonObj = {
                            'name' : this.supportPersonName,
                            'mnemonic' : this.supportPersonMnemonic,

            }

            try{
                var responseData = await supportPersonApi.create({
                        newSupportPerson: supportPersonObj,
                    });
                this.$notify({
                    group: "notificationGroup",
                    type: "success",
                    title: "Miembro de soporte guardado exitosamente.",
                });
            }
            catch (e) {
                console.log(e.response.data);
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error",
                    text: e.response.data.errorMessage,
                });
            } finally {
                this.adding = false;
                this.supportPersonName = ""
                this.supportPersonMnemonic = ""
                this.fetchSupportPeople()
            }

        },


        onRowEdit(supportPerson_id){
            console.log(supportPerson_id)
        },

        async onRowDelete(){
            await supportPersonApi.delete(this.supportPersonId)
            this.$modal.hide('deleteConfirmation')
            await this.fetchSupportPeople();
        },

        doNotDelete (){
            this.$modal.hide('deleteConfirmation')
        },

        async doDelete(){
            await this.onRowDelete()
        },


        async onDeleteSupportPeopleClick (row) {
            this.supportPersonId = row
            this.$modal.show('deleteConfirmation')

        },




    }

}
</script>
