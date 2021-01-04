@extends ('layouts.app')

@section ('content')
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

</div>
@endsection
