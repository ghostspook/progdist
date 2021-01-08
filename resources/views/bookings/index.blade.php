@extends ('layouts.app')

@section ('content')
<div>
    <table class="table">
        <tr>
            <th>Fecha</th>
            <th>Área</th>
            <th>Profesor</th>
            <th>Programa</th>
            <th>Inicia</th>
            <th>Termina</th>
            <th>Aula Física</th>
            <th>Aula Virtual</th>
            <th>Link</th>
            <th>Password</th>
            <th>Soporte Coordinación</th>
            <th>Tipo</th>
            <th>Soporte Académico</th>
            <th>Tipo</th>
            <th>Soporte TI</th>
            <th>Tipo</th>
        </tr>

        @foreach ($bookings as $booking)
            <tr>
                <td> {{ $booking->date }}</td>
                <td> {{ $booking->area->mnemonic}} </td>
                <td> {{ $booking->instructor->mnemonic }}  </td>
                <td> {{ $booking->program->mnemonic}}  </td>
                <td> {{ $booking->start_time }}  </td>
                <td> {{ $booking->end_time }}  </td>
                <td> {{ $booking->physicalRoom->mnemonic }}  </td>
                <td>  {{ $booking->virtualMeetingLink->virtualRoom->mnemonic }}  </td>

                <td> <a href="{{ $booking->virtualMeetingLink->link }}"> {{ $booking->virtualMeetingLink->link }} </a>  </td>
                <td> {{ $booking->virtualMeetingLink->password }}  </td>



                @foreach ($booking->supportPersons as $supportPerson)
                    @if ($supportPerson->support_person_role_id == 1 )
                         <td>
                            {{ $supportPerson->mnemonic }}
                         </td>
                    @endif


                    @if ($supportPerson->support_person_role_id == 2 )
                        <td>
                            {{ $supportPerson->mnemonic }}
                        </td>
                    @endif


                    @if ($supportPerson->support_person_role_id == 3 )
                        <td>
                            {{ $supportPerson->mnemonic }}
                        </td>
                    @endif

                @endforeach

            </tr>
        @endforeach

    </table>

</div>
@endsection
