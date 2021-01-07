@extends ('layouts.app')

@section ('content')
<form action="{{ route('bookings.create') }}" method="post">
    @csrf

    <div>
        <label for="bookingDate"> Fecha: </label>
        <input type="date" id="bookingDate" name="bookingDate" min="2020-01-01" value="{{ old('bookingDate') }}">

        <label for="startTime"> Hora de Inicio: </label>
        <select id="startTime" name="startTime" >
            @include('layouts.partials._dropDownStartTime')
        </select>

        <label for="endTime"> Hora de Fin: </label>
        <select id="endTime" name="endTime" >
           @include('layouts.partials._dropDownEndTime')
        </select>

    </div>


    <div>
        <label for="Area"> Área: </label>
        <select id="Area" name="Area" >
        <option value="Seleccione"> Seleccione </option>
            @foreach ($areas as $area)
                <option value="{{$area->mnemonic}}"  {{ old('Area') == "$area->mnemonic" ? 'selected' : '' }} > {{$area->mnemonic}}</option>
            @endforeach
        </select>

        <label for="instructor"> Profesor: </label>
        <select id="instructor" name="instructor" >
            <option value="Seleccione"> Seleccione </option>
            @foreach ($instructors as $instructor)
                <option value="{{$instructor->mnemonic}}"  {{ old('instructor') == "$instructor->mnemonic" ? 'selected' : '' }} > {{$instructor->mnemonic}}</option>
            @endforeach
        </select>

        <label for="requestedBy"> Solicitada por: </label>
        <input type="text" id="requestedBy" name="requestedBy" value="{{ old('requestedBy') }}">


    </div>


    <div>
        <label for="program"> Programa: </label>
        <select id="program" name="program" >
            <option value="Seleccione"> Seleccione </option>
            @foreach ($programs as $program)
                <option value="{{$program->mnemonic}}"  {{ old('program') == "$program->mnemonic" ? 'selected' : '' }} > {{$program->mnemonic}}</option>
            @endforeach
        </select>

        <label for="topic"> Tema (si es reunión): </label>
        <input type="text" id="topic" name="topic" value="{{ old('topic') }}">


        <label for="physicalRoom"> Aula Física: </label>
        <select id="physicalRoom" name="physicalRoom" >
            <option value="Seleccione"> Seleccione </option>
            @foreach ($physicalRooms as $physicalRoom)
                <option value="{{$physicalRoom->mnemonic}}"  {{ old('physicalRoom') == "$physicalRoom->mnemonic" ? 'selected' : '' }} > {{$physicalRoom->mnemonic}}</option>
            @endforeach
        </select>


        <label for="virtualRoom"> Aula Virtual: </label>
        <select id="virtualRoom" name="virtualRoom" >
            <option value="Seleccione"> Seleccione </option>
            @foreach ($virtualRooms as $virtualRoom)
                <option value="{{$virtualRoom->mnemonic}}"  {{ old('virtualRoom') == "$virtualRoom->mnemonic" ? 'selected' : '' }} > {{$virtualRoom->mnemonic}}</option>
            @endforeach
        </select>

    </div>

    <div>
        <label for="CoordSupport"> Soporte Coordinación: </label>
        <select id="CoordSupport" name="CoordSupport" >
            <option value="Seleccione"> Seleccione </option>
            <@foreach ($supportPeople as $supportPerson)
                <option value="{{$supportPerson->mnemonic}}"  {{ old('CoordSupoort') == "$supportPerson->mnemonic" ? 'selected' : '' }} > {{$supportPerson->mnemonic}}</option>
             @endforeach
        </select>

        <label for="CoordSupportType"> Tipo: </label>
        <select id="CoordSupportType" name="CoordSupportType" >
            <option value="Seleccione"> Seleccione </option>
            <option value="Físico"  {{ old('CoordSupportType') == "Físico" ? 'selected' : '' }} > Físico</option>
            <option value="Virtual"  {{ old('CoordSupportType') == "Virtual" ? 'selected' : '' }} > Virtual</option>
        </select>

        <label for="AcademicSupport"> Soporte Académico: </label>
        <select id="AcademicSupport" name="AcademicSupport" >
            <option value="Seleccione"> Seleccione </option>
            <@foreach ($supportPeople as $supportPerson)
                <option value="{{$supportPerson->mnemonic}}"  {{ old('AcademicSupport') == "$supportPerson->mnemonic" ? 'selected' : '' }} > {{$supportPerson->mnemonic}}</option>
             @endforeach
        </select>

        <label for="AcademicSupportType"> Tipo: </label>
        <select id="AcademicSupportType" name="AcademicSupportType" >
            <option value="Seleccione"> Seleccione </option>
            <option value="Físico"  {{ old('AcademicSupportType') == "Físico" ? 'selected' : '' }} > Físico</option>
            <option value="Virtual"  {{ old('AcademicSupportType') == "Virtual" ? 'selected' : '' }} > Virtual</option>
        </select>

        <label for="ITSupport"> Soporte TI: </label>
        <select id="ITSupport" name="ITSupport" >
            <option value="Seleccione"> Seleccione </option>s
            <@foreach ($supportPeople as $supportPerson)
                <option value="{{$supportPerson->mnemonic}}"  {{ old('ITSupport') == "$supportPerson->mnemonic" ? 'selected' : '' }} > {{$supportPerson->mnemonic}}</option>
            @endforeach
        </select>

        <label for="ITSupportType"> Tipo: </label>
        <select id="ITSupportType" name="ITSupportType" >
            <option value="Seleccione"> Seleccione </option>
            <option value="Físico"  {{ old('ITSupportType') == "Físico" ? 'selected' : '' }} > Físico</option>
            <option value="Virtual"  {{ old('ITSupportType') == "Virtual" ? 'selected' : '' }} > Virtual</option>
        </select>

    </div>

    <div>
        <button type="submit"> Registrar</button>
        <input type="reset">
    </div>

    <div>
        <table style="width:100%">
            <tr>
                <th>Fecha</th>
                <th>Área</th>
                <th>Profesor</th>
                <th>Programa</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Aula Física</th>
                <th>Aula Virtual</th>
                <th>Link</th>
                <th>Password</th>
                <th>Soporte Coordinación</th>
                <th>Soporte Académico</th>
                <th>Soporte TI</th>
            </tr>

            @foreach ($bookings as $booking)
                <tr>
                    <th> {{ $booking->date }}</th>
                    <th> {{ $booking->area->mnemonic}} </th>
                    <th> {{ $booking->instructor->mnemonic }}  </th>
                    <th> {{ $booking->program->mnemonic}}  </th>
                    <th> {{ $booking->start_time }}  </th>
                    <th> {{ $booking->end_time }}  </th>
                    <th> {{ $booking->physicalRoom->mnemonic }}  </th>
                    <th>  {{ $booking->virtualMeetingLink->virtualRoom->mnemonic }}  </th>

                    <th> <a href="{{ $booking->virtualMeetingLink->link }}"> {{ $booking->virtualMeetingLink->link }} </a>  </th>
                    <th> {{ $booking->virtualMeetingLink->password }}  </th>



                    @foreach ($booking->supportPersons as $supportPerson)
                        @if ($supportPerson->support_person_role_id == 1 )
                             <th>
                                {{ $supportPerson->mnemonic }}
                             </th>
                        @endif


                        @if ($supportPerson->support_person_role_id == 2 )
                            <th>
                                {{ $supportPerson->mnemonic }}
                            </th>
                        @endif


                        @if ($supportPerson->support_person_role_id == 3 )
                            <th>
                                {{ $supportPerson->mnemonic }}
                            </th>
                        @endif

                    @endforeach

                </tr>
            @endforeach

        </table>

        {{ $bookings->links()}}

    </div>



    @if  ( $errors->any() )
        <ul>
        @foreach ($errors->all() as $error)
            <li>
                <p>{{  $error }}</p>
            </li>
        @endforeach
        </ul>
    @endif
</form>

@endsection
