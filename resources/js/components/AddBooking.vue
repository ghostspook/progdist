<template>
   <div>
       <form>
            <div class="card">
                <h5 class="card-header">
                    <span>Nueva Sesión </span>
                </h5>
                <div class="card-body">
                    <div class="row">
                      
                        <div class="form-group col-md-2">
                              <label for="bookingDate">Fecha</label>
                            <input type="Date" class="form-control" v-model="startDate" @change="onStartDateChange"/>
                        </div>
                  
                        <div class="col-md-2" >
                            <label for="program">Programa</label>
                            <select  class="form-control" id="program" v-model="selectedProgram" @blur="onProgramUnfocus()">
                                <option :value="null">Ninguno</option>
                                <option v-for="p in sortedPrograms" 
                                                v-bind:key="p.id"
                                                :value="p.id"
                                                >
                                                {{ p.mnemonic }}
                                </option>
                            </select>
                        </div>

                        
                        <div class="col-md-3 form-group" >
                            <label for="topic">Tema</label>
                            <input type="text"  class="form-control" id="topic" v-model="topic" />
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="startTime">Inicia</label>
                            <input class="form-control text-center" type="time" id="startTime"  />
                        </div>

                        <div class="col-md-2 form-group">
                            <label for="endTime">Termina</label>
                            <input class="form-control text-center" type="time" id="endTime" />
                        </div>   
                                         
                    </div>

                    <div class="row">
                        <div class="col-md-2" >
                            <label for="areas">Área</label>
                            <select  class="form-control" id="areas" v-model="selectedArea" @blur="onAreaUnfocus()" @change="onAreaChange()">
                                <option :value="null">Ninguna</option>
                                <option v-for="a in sortedAreas" 
                                                v-bind:key="a.id"
                                                :value="a.id"
                                                >
                                                {{ a.mnemonic }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2" >
                            <label for="instructors">Profesor</label>
                            <select  class="form-control" id="instructors" v-model="selectedInstructor" @blur="onInstructorUnfocus()">
                                <option :value="null">Ninguno</option>
                                <option v-for="i in sortedInstructors" 
                                                v-bind:key="i.id"
                                                :value="i.id"
                                                >
                                                {{ i.mnemonic }} - {{ i.name }} 
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2" >
                            <label for="physicalRoom">Aula Física</label>
                            <select  class="form-control" id="physicalRoom" v-model="selectedPhysicalRoom" @blur="onPhysicalRoomUnfocus()" >
                                <option :value="null">Ninguna</option>
                                <option v-for="room in sortedPhysicalRooms" 
                                                v-bind:key="room.id"
                                                :value="room.id"
                                                >
                                                {{ room.mnemonic }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2" >
                            <label for="virtualRoom">Aula Virtual</label>
                            <select  class="form-control" id="virtualRoom" v-model="selectedVirtualRoom" @click="virtualRoomModalisOpen=true" >
                                <!-- <option :value="b.virtual_room_id">{{ b.virtual_room_mnemonic}}</option> -->
                            </select>
                            
                        </div>

                        <div class="col-md-3 form-group" >
                            <label for="supportPeople">Soporte</label>
                            <input type="text"  class="form-control" id="supportPeople" v-model="topic" />
                        </div>

                       

                    </div>  
                    
                </div>
            </div>
       </form>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>