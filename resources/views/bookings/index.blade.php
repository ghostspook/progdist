@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div id="app">
    <new-booking></new-booking>
</div>
<div>
    <table class="table">
        <tr>
            <th>Día</th>
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
            <th>Soporte</th>
        </tr>

        @foreach ($bookings as $booking)
            <tr>
                <td> {{ $booking->booking_date->dayName }}</td>
                <td> {{ $booking->booking_date->format('d-M-Y') }}</td>
                <td> {{ !$booking->area ? "" : $booking->area->mnemonic}} </td>
                <td> {{ !$booking->instructor ? "" :  $booking->instructor->mnemonic }}  </td>
                <td> {{ !$booking->program ? "" : $booking->program->mnemonic}}  </td>
                <td> {{ $booking->start_time->format('H:i') }}  </td>
                <td> {{ $booking->end_time->format('H:i') }}  </td>
                <td> {{ !$booking->physicalRoom ? "" : $booking->physicalRoom->mnemonic }}  </td>
                <td>  {{ !$booking->virtualMeetingLink ? "" : $booking->virtualMeetingLink->virtualRoom->mnemonic }}  </td>

                <td>
                    @if($booking->virtualMeetingLink)
                    <a href="{{ $booking->virtualMeetingLink->link }}"> {{ $booking->virtualMeetingLink->link }} </a>
                    @endif
                </td>
                <td> {{ !$booking->virtualMeetingLink ? "" : $booking->virtualMeetingLink->password }}  </td>
                <td>
                        @markdown($booking->getSupportPersonsSummary())
                </td>
            </tr>
        @endforeach

    </table>
    {{ $bookings->links() }}
</div>
@endsection
