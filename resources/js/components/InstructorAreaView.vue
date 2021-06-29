<template>
    <div class="card">
        <div class="row">
            <div class="col-md-7">
                <instructor :canCreateAndEdit="canCreateAndEditBookings" :area-added="areaAdded"  > </instructor>
            </div>
            <div class="col-md-5">
                <areas-for-instructor :canCreateAndEdit="canCreateAndEditBookings" @area-added="onAreaAdded" ></areas-for-instructor>
            </div>
        </div>
        <notifications group="notificationGroup" position="top center" />
    </div>
</template>

<script>
import Instructor from './Instructor.vue'
import AreasForInstructor from './Area.vue'

import userApi from '../services/user'


export default {
    components: {
        Instructor,
        AreasForInstructor
    },
     props: {


    },
    data() {
        return {
            user: null,
            areaAdded: false,
        };
    },
    computed: {

        canCreateAndEditBookings() {
            return (!this.user) ? false : this.user.authorized_account.can_create_and_edit_bookings == 1
        },
    },
    async mounted() {
        this.getUserInfo()
        console.log("user",this.canCreateAndEditBookings)

     },
    methods: {
        onAreaAdded (){
            this.areaAdded = true
        },

        async getUserInfo (){
              if (!this.user) {
                this.user = await userApi.getMyUser()
            }

        },
    }

}
</script>
