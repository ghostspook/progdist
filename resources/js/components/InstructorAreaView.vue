<template>
    <div class="card">
        <div class="row">
            <div class="col-md-7">
                <instructor :area-added="areaAdded"  > </instructor>
            </div>
            <div class="col-md-5">
                <areas-for-instructor @area-added="onAreaAdded" ></areas-for-instructor>
            </div>
        </div>
        <notifications group="notificationGroup" position="top center" />
    </div>
</template>

<script>



export default {
    components: {


    },
     props: {

    },
    data() {
        return {
            areaAdded: false,
        };
    },
    computed: {

    },
    async mounted() {


     },
    methods: {
        onAreaAdded (){
            this.areaAdded = true
        }
    }

}
</script>
