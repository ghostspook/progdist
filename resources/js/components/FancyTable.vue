<template>
<div>


    <table class="table table-hover">
        <thead>
            <tr>
                <th v-for="col in visibleColumns" :key=col.index scope="col" >
                  {{col.label}}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key=row.index>
                <td> {{row.booking_date }} </td>
            </tr>

        </tbody>
    </table>


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
    },

    computed: {
        visibleColumns(){
            return this.columns.filter( col => (col.hidden==false || col.hidden==undefined ))
        }
    }


}
</script>

<style>

</style>
