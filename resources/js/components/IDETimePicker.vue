<template>
    <div>
        <form>
            <select class="form-control" id="IDETimePicker" >
                    <option :value="null">Ninguna</option>
                    <option v-for="t in timeListing"  v-bind:key="t.id" :value="t.hour">{{ t.hour }} </option>
            </select>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: [String, Date]
            }
        },

        data () {
            return {

            }
        },

        computed: {


                  timeListing (initialTime=7 , finalTime=23, minutesInterval =5) {
                            initialTime=7;
                            // console.log(initialTime);
                            // console.log(finalTime);
                            var timeList = []
                            timeList.push({id: initialTime + ":" +"00", hour: initialTime + ":" +"00" }) ;
                            var nextMinutes = minutesInterval
                            var minutePrecedingZero=""
                            for (var i=initialTime; i <= finalTime;i++ ){
                                while(nextMinutes < 60){
                                    minutePrecedingZero = (nextMinutes < 10  ? "0" : "" )

                                    timeList.push({id: i + ":" + minutePrecedingZero +  nextMinutes ,
                                                   hour: i + ":" + minutePrecedingZero +  nextMinutes
                                                 })
                                    nextMinutes = nextMinutes + minutesInterval
                                }
                                nextMinutes = 0
                            }
                            // console.log(timeList);

                            return timeList;

                    }


        },

        mounted() {
            console.log('Component mounted.')
          //  this.timeInterval();

        },

        method: {

                }

    }

</script>
