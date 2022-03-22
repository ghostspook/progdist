<template>
<div >
    <div class="flex-row mt-1 ml-1 mb-1" v-if="selectedRows.length>0">
        <span class="alert alert-info mt-1 ml-1 mb-1" role="alert" >
            {{ selectedRows.length}} registro(s) seleccionado(s).
            <a  href="#" class="ml-4 reset-link pe-auto" @click="clearSelection()">Deseleccionar </a>
        </span>
    </div>
    <div class="mt-4" >
        <table class="table table-hover table-responsive mt-1">
            <thead class="mt-1">
                <tr>
                    <th  v-if="selectable" >
                       <td>
                            <div class="form-check">
                                <input class="form-check-input" ref="selectAllRows" type="checkbox"  @change="changeSelectAllRows($event)" id="flexCheckDefault">
                            </div>
                       </td>

                    </th>

                    <!-- <th v-if="$scopedSlots.rowActions">
                        Acciones
                    </th> -->

                    <th v-for="col in visibleColumns" :key=col.index scope="col"  >



                            <!-- <i class="fa fa-solid fa-arrow-up"></i> -->

                            <span @click="col.sortable ? sortField(col.field, col.label) :''">
                            {{col.label}}
                            </span>
                       <i v-if="sortedBy==col.label && ascSortOrder" class="fa fa-solid fa-arrow-up"></i>
                       <i v-if="sortedBy==col.label && !ascSortOrder" class="fa fa-solid fa-arrow-down"></i>
                            <!-- <i class="fa fa-solid fa-sort-down"></i>
                            <i class="fa fa-solid fa-sort-up"></i> -->
                            <!-- <i class="fa fa-solid fa-arrow-down-arrow-up"></i> -->
                    </th>

                </tr>
                <tr>
                    <!-- <th v-if="selectable">
                    </th> -->
                    <!-- <th v-if="$scopedSlots.globalActions">
                    </th> -->
                    <th >
                        <slot v-if="$scopedSlots.globalActions && selectedRows.length>0" name="globalActions" :rows="selectedRows">

                        </slot>

                    </th>

                    <th v-for="col in visibleColumns" :key=col.index scope="col"  >
                        <input  v-if="col.filterable" type="text"
                            :size="col.label.length+5"
                            :placeholder="col.label | toPlaceHolderStr"
                            @keyup="filterField(col.field, $event)"
                        />
                    </th>
                </tr>

            </thead>
            <tbody v-if="fancyTableData.length>0">

                <tr v-for="(row,index) in fancyTableData" :key="index"  @mouseover="hoverIndex = index" @mouseleave="hoverIndex = null">
                    <td class="d-flex">
                        <div class="form-check" v-if="selectable">
                            <input class="form-check-input" type="checkbox" :value="getIdField(row)" v-model="selectedRows" @change="changeSelectedRows($event)" id="flexCheckDefault">
                        </div>

                        <div v-if="$scopedSlots.rowActions" v-show="hoverIndex==index && selectedRows.length==0" >
                            <slot name="rowActions" :row-id="getIdField(row)">
                            </slot>
                        </div>
                    </td>

                    <template v-for="(item,key,index) in row"  >
                        <td v-if="!item.hidden" :key="index">
                            <div v-if="item.html" v-html="item.value">
                            </div>
                            <div v-else>
                                {{ item.value }}
                            </div>
                        </td>
                    </template>
                </tr>

            </tbody>

            <tfoot>
                <tr>
                    <td colspan="3">
                        <label  class="form-select" for="rowsperpage"> Sesiones por página: </label>
                        <select id="rowsperpage" @change="perPageChange($event)">
                            <option v-for="perPage in perPageDropdown" :key="perPage.index">
                                {{perPage}}
                            </option>
                        </select>
                    </td>
                    <td colspan="3">
                        Mostrando del {{ showingRowsFrom }} al {{ showingRowsTo }} de un total de {{ totalRows }} sesiones
                    </td>
                    <td colspan="2">
                        <button v-if="availablePreviousPage" type="button" @click="pageChange('previous')" >
                            <span>Anterior</span>
                        </button>
                        <button v-if="availableNextPage" type="button" @click="pageChange('next')" >
                            <span>Siguiente</span>
                        </button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
</template>

<script>



export default {
    props: {
        columns: {
            type: Array,
            default: []
        },
        rows: {
            type: Array,
            default: []
        },
        totalRows:{
            type: Number,
            default: 0
        },
        selectable:{
            type: Boolean,
            default: false
        },
        idField: {
            type: String,
            default: 'id'
        },
        clearSelectedRows: {
            type: Boolean,
            default: false
        },
        paginationOptions:{
            type: Object,
            default: function () {
                return { enable: true , perPageDropdown: [10,25,50] , dropdownAllowAll: false }
            }
        }



    },

    data (){
        return {
            currentPage: 1,
            currentPerPage: 10,
            columnFilters: {},
            ascSortOrder: true,
            sortedBy: '',
            selectedRows: [],


            hoverIndex:null,



        }
    },

    computed: {

        visibleColumns(){
            return this.columns.filter( col => (col.hidden==false || col.hidden==undefined ))
        },


        perPageDropdown (){
            return this.paginationOptions.perPageDropdown
        },

        availableNextPage (){
            return this.currentPage + 1 <= Math.ceil(this.totalRows / this.currentPerPage) ? true : false
        },

        availablePreviousPage (){
            return this.currentPage > 1 ? true : false
        },

        showingRowsFrom(){
            return this.currentPage == 1 ? 1 : this.currentPage * this.currentPerPage - this.currentPerPage + 1

        },

        showingRowsTo(){
            return this.currentPage * this.currentPerPage <= this.totalRows ?
                                    this.currentPage * this.currentPerPage :
                                    this.totalRows

        },

        fancyTableData(){
            // var headers= [  {field: 'booking_date'},   {field: 'day'} ,]
            // var rows = [ {day: 'viernes', booking_date: '2022-03-12'} ,
            //             {day: 'martes', booking_date: '2022-04-12' }
            //             ]
            var fancyData= []


            this.rows.forEach(row => {
                var fancyItem ={}
                var i= 0;
                this.columns.forEach(col => {

                        fancyItem[i] = { field: col.field  }

                        //format values if there is a defined format method by user
                        if (col.formatFn){

                            fancyItem[i].value = this.$parent[col.formatFn](row[col.field])
                        }
                        else {
                            fancyItem[i].value = row[col.field]
                        }

                        if (col.html) {
                            fancyItem[i].html = true
                        }

                        if (col.hidden){
                            fancyItem[i].hidden = true
                        }
                        else {
                            fancyItem[i].hidden = false
                        }
                        i++


                });
                fancyData.push(fancyItem)

            });

            return fancyData;


        }


    },

    filters: {
        toPlaceHolderStr(value) {
            return 'Filtrar'
            return 'Filtrar por ' + value
        },
    },

    watch: {
        clearSelectedRows:
            function(val) {
                this.selectedRows = []
            },

        rows:
            function(val) {
                this.setGlobalCheckBoxState()

            },

        selectedRows:
            function(val) {

                this.setGlobalCheckBoxState()

            },

    },


    methods: {

        getIdField(row){
            //convert row Object into an Array so it can be Filtered
            const fields = Object.entries(row);

            var id = fields.filter ( f => f[1].field == this.idField).length>0 ?
                    fields.filter ( f => f[1].field == this.idField)[0][1].value : null

            return id

        },

        getAllIds(){
             //convert row Object into an Array so it can be Filtered

            var ids =[];
            this.rows.forEach(r => {
                  ids.push ( r[this.idField])

              });

            return ids

        },

        setGlobalCheckBoxState(){

            var currentPageAllIds = this.getAllIds()
            var currentPageSelectedRows = this.selectedRows.filter(r=> currentPageAllIds.some( id=> id==r) )
            var checkState = 0



            if ( currentPageSelectedRows.length==0){
                checkState =0  //All unselected State
            }

            if (currentPageSelectedRows.length>0){


                if ( currentPageSelectedRows.length == currentPageAllIds.length ){
                    checkState =  1 //All selected state
                }
                else {
                    checkState = 2 //Indeterminate selected state
                }
            }

            if (checkState==2){
                this.$refs.selectAllRows.indeterminate = true

            }
            else if (checkState==1){
                this.$refs.selectAllRows.checked = true
                this.$refs.selectAllRows.indeterminate = false

            }
            else if (checkState==0){
                this.$refs.selectAllRows.checked = false
                this.$refs.selectAllRows.indeterminate = false

            }


        },

        clearSelection (){
             this.selectedRows = []
        },

        filterField (field, e){

            this.selectedRows = [] //clear selected rows

            this.columnFilters[field] = e.target.value
            this.currentPage = 1
            var params = { currentPage: this.currentPage, currentPerPage: this.currentPerPage ,
                            total: this.totalRows, columnFilters: this.columnFilters }


            this.$emit('on-column-filter',params)


        },

        sortField(field, label){

            this.ascSortOrder= !this.ascSortOrder;

            this.sortedBy = label


            var sortArray = []

            sortArray.push ({'field': field ,
                            'type': this.ascSortOrder ? 'asc' : 'desc'
                            })

            var params = { currentPage: this.currentPage, currentPerPage: this.currentPerPage ,
                            total: this.totalRows, columnFilters: this.columnFilters ,
                            sort: sortArray
                            }







            this.$emit('on-sort-change',params)


        },


        perPageChange (e){
            this.currentPerPage = e.target.value
            this.currentPage = 1
            var params = { currentPage: this.currentPage, currentPerPage: this.currentPerPage , total: this.totalRows }
            this.$emit('on-per-page-change',params)



        },

        pageChange(e){


            this.setGlobalCheckBoxState()


            if ( e=="next") {

                if (this.currentPage + 1 <= Math.ceil(this.totalRows / this.currentPerPage)) {

                    this.currentPage++
                }
                else
                    return

            }
            else if (e=="previous") {
                if (this.currentPage==1 ) {

                    return
                }
                else {
                this.currentPage--
                }
            }

            var params = { currentPage: this.currentPage , currentPerPage: this.currentPerPage, total: this.totalRows }
            this.$emit('on-page-change',params)



        },

        changeSelectedRows(e){


        },

        changeSelectAllRows (e){



            if (e.target.checked) {
                this.getAllIds().forEach(r => {
                    if (r!= null && this.selectedRows.filter(id => id==r).length==0) {
                        this.selectedRows.push(r)
                    }
                 });


            }
            else {
                this.selectedRows.forEach((r,index) => {
                    if ( this.getAllIds().filter( id => r==id).length==1) {
                        this.selectedRows = this.selectedRows.filter(f => f!=r)
                    }
                });


            }

        },



    }


}
</script>

<style>

</style>
