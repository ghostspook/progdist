<template>
    <div class="card">
        <h5 class="card-header">
            Áreas
        </h5>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="areaName">Nombre</label>
                            <input type="text" size="15" v-model="areaName">
                        </div>
                        <div class="col-md-4">
                            <label for="areaMnemonic">Mnemónico</label>
                            <input type="text" size="8" v-model="areaMnemonic">
                        </div>
                        <div class="row">
                            <div class="col-md-4 mt-4">
                                <button
                                    v-if="canCreateAndEdit"
                                    :disabled="adding"
                                    class="btn btn-success"
                                    @click="onAddAreaClick"
                                    >
                                    Añadir
                                </button>
                            </div>
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
                        <!-- <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field == 'actions'">
                                <a class="edit btn btn-sm btn-primary"  @click="onRowEdit(props.row.booking_id)"><i class="fa fa-edit"></i></a>
                                <a class="edit btn btn-sm btn-danger"  @click="onRowDelete(props.row.booking_id)"><i class="fa fa-trash"></i></a>
                            </span>

                        <span v-else>
                            {{props.formattedRow[props.column.field]}}
                            </span> -->
                        <!-- </template> -->
                    </vue-good-table>
                </div>
            </div>
        </div>
        <!-- <notifications group="notificationGroup" position="top center" /> -->
    </div>
</template>

<script>


import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'

import areasApi from "../services/area";



export default {
    components: {
        VueGoodTable,

    },
    props: {
        canCreateAndEdit: {
            type: Boolean,
            required: true,

        },


    },
    data() {
        return {


            areaName: "",
            areaMnemonic: "",

            adding: false,

            columns: [
                        {
                            label: 'area_id',
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
                rowsPerPage: 10,

            }
        };
    },
    computed: {



    },
    async mounted() {

        await this.fetchAreas()

     },
    methods: {
        async fetchAreas() {
            try {
                let data  = await areasApi.getAllPaged(this.serverParams);
                this.rows = data.data
                this.totalRecords = data.total

            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de áreas"
                });
            }
        },




        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);

        },

        async onPageChange(params) {
            this.updateParams({page: params.currentPage});
            await this.fetchAreas()
        },

        async onPerPageChange(params) {
            this.updateParams({rowsPerPage: params.currentPerPage});
            await this.fetchAreas()
        },

        async onSortChange(params) {

           this.updateParams({
                sort: [{
                type: params[0].type,
                field: params[0].field,
             }],
            });

            await this.fetchAreas()

        },

        async onColumnFilter(params) {
            this.updateParams(params);
            await this.fetchAreas()

        },

        async onAddAreaClick() {
            this.adding = true

            if ( this.areaName == "" || this.areaMnemonic == ""){
                alert("Debe ingresar el nombre y mnemónico del área")
                this.adding = false
                return
            }


            var areaObj = {
                            'name' : this.areaName,
                            'mnemonic' : this.areaMnemonic,
            }

            try{
                var responseData = await areasApi.create({
                        newArea: areaObj,
                    });
                this.$emit('area-added')
                this.$notify({
                    group: "notificationGroup",
                    type: "success",
                    title: "Área guardada exitosamente.",
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
                this.areaName = ""
                this.areaMnemonic = ""
                this.fetchAreas()
            }

        },




    }

}
</script>
