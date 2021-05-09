<template>
<div class="card">
    <h5 class="card-header">
        Profesores
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <label for="instructorName">Nombre</label>
                        <input type="text" v-model="instructorName">
                    </div>
                    <div class="col-md-3">
                        <label for="instructorMnemonic">Mnemónico</label>
                        <input type="text" v-model="instructorMnemonic">
                    </div>
                    <div class="col-md-3">
                        <multiselect
                            id="areas"
                            v-model="selectedAreas"
                            :options="selectableAreas"
                            track-by="label"
                            label="label"
                            :multiple="true"
                            :taggable="true"
                            :showLabels="true"
                            :hide-selected="true"
                        ></multiselect>
                    </div>
                    <div class="col-md-3">
                        <button
                            :disabled="adding"
                            class="btn btn-success"
                            @click="onAddInstructorClick"
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
    </div>
</template>

<script>

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

import 'vue-good-table/dist/vue-good-table.css'
import { VueGoodTable } from 'vue-good-table'

import instructorsApi from "../services/instructor";
import areasApi from "../services/area";



export default {
    components: {
        Multiselect,
        VueGoodTable,

    },
     props: {

    },
    data() {
        return {

            areas: [],
            selectedAreas: [],


            instructorName: "",
            instructorMnemonic: "",

            adding: false,

            columns: [
                        {
                            label: 'instructor_id',
                            field: 'instructor_id',
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
        await this.fetchInstructors()
        await this.fetchAreas()
        console.log("areas", this.areas)

     },
    methods: {
        async fetchAreas() {
            try {
                this.areas = await areasApi.getAll();
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de profesores"
                });
            }
        },
        async fetchInstructors() {
            try {
                let data  = await instructorsApi.getAll(this.serverParams);
                this.rows = data.data
                this.totalRecords = data.total
            } catch(e) {
                console.log(e)
                this.$notify({
                    group: "notificationGroup",
                    type: "error",
                    title: "Error de red",
                    text:   "No se pude descargar la lista de profesores"
                });
            }
        },



        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);

        },

        async onPageChange(params) {
            this.updateParams({page: params.currentPage});
            await this.fetchInstructors()
        },

        async onPerPageChange(params) {
            this.updateParams({rowsPerPage: params.currentPerPage});
            await this.fetchInstructors()
        },

        async onSortChange(params) {

           this.updateParams({
                sort: [{
                type: params[0].type,
                field: params[0].field,
             }],
            });

            await this.fetchInstructors()

        },

        async onColumnFilter(params) {
            this.updateParams(params);
            await this.fetchInstructors()

        },

        async onAddInstructorClick() {
            this.adding = true

            var instructorObj = {
                            'name' : this.instructorName,
                            'mnemonic' : this.instructorMnemonic,
                            'areas' : this.selectedAreas
            }

            try{
                var responseData = await instructorsApi.create({
                        newInstructor: instructorObj,
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
            }

        },




    }

}
</script>
