@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div id="app">
    <new-booking ref="bk"></new-booking>

    {{-- <div > <button  id="external-button" @click="$refs.bk.onNewClick()">External Button</button></div> --}}

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
                <th width="60"></th>
            </tr>
        </thead>
    </table>
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
                    { data: 'support_people', name: 'support_people', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        function onBookingClick(bookingId) {
            // Invocado por data tables (a nivel de fila)
            console.log(bookingId);
            app.$refs.bk.onEdit(bookingId);
        }

    </script>
@endpush
