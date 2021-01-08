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

                    @if ($booking->getCoordinatingSupportPerson())
                        <td>
                            {{ $booking->getCoordinatingSupportPerson()->supportPerson->mnemonic }}
                        </td>
                        <td>
                            {{ $booking->getCoordinatingSupportPerson()->supportTypeText() }}
                        </td>
                    @else
                        <td></td>
                        <td></td>
                    @endif


                    @if ($booking->getAcademicSupportPerson())
                        <td>
                            {{ $booking->getAcademicSupportPerson()->supportPerson->mnemonic }}
                        </td>
                        <td>
                            {{ $booking->getAcademicSupportPerson()->supportTypeText() }}
                        </td>
                    @else
                        <td></td>
                        <td></td>
                    @endif


                    @if ($booking->getTiSupportPerson())
                        <td>
                            {{ $booking->getTiSupportPerson()->supportPerson->mnemonic }}
                        </td>
                        <td>
                            {{ $booking->getTiSupportPerson()->supportTypeText() }}
                        </td>
                    @else
                        <td></td>
                        <td></td>
                    @endif

            </tr>
        @endforeach

    </table>

</div>
@endsection
