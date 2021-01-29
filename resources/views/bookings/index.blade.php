@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div id="app">
    <new-booking></new-booking>
</div>
<div>
   <table id="myDataTable">
        <thead>
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
        </thead>
    </table>

    {{-- <table class="myDataTable">
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
        </tr> --}}

        {{-- @foreach ($bookings as $booking)
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
    {{ $bookings->links() }} --}}
</div>
@endsection

@push('js')
    <script type="text/javascript">
        $(function() {
            $('#myDataTable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json'
                },
                ajax: '{!! route('bookings.index.datatable') !!}',
                columns: [
                       { data: 'day_name', name: 'day_name', orderable: false, searchable: false },
                      { data: 'booking_date', name: 'booking_date' },
                    { data: 'area.mnemonic', name: 'area.mnemonic' , orderable: true, searchable: true},
                    { data: 'instructor.mnemonic', name: 'instructor.mnemonic' },
                    { data: 'program.mnemonic', name: 'program.mnemonic', orderable: true, searchable: true },
                     { data: 'start_time', name: 'start_time', orderable: true, searchable: false },
                     { data: 'end_time', name: 'end_time', orderable: true, searchable: false },
                    { data: 'physical_room.mnemonic', name: 'physicalRoom.mnemonic', defaultContent: "" },
                   { data: 'virtual_meeting_link.virtual_room.mnemonic', name: 'virtualMeetingLink.virtualRoom.mnemonic', defaultContent: "" },
                   { data: 'link', name: 'link', orderable: false, searchable: false },
                   { data: 'virtual_meeting_link.password', name: 'virtualMeetingLink.password', orderable: false, searchable: false, defaultContent: ""},
                   { data: 'support_people', name: 'support_people', orderable: false, searchable: false }

                ]
            });
        });
    </script>
@endpush
