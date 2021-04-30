@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
    <a class="btn btn-success mt-3 mb-4 ml-2" href="{{ route('programs.create') }}"><i class="fa fa-plus"></i> Nuevo</a>
    <table id="myDataTable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Mnemónico</th>
                <th>Nombre corto</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th style="width: 100px;"></th>
            </tr>
        </thead>
    </table>
    <div id="app"></div>
@endsection

@push('js')
    <script type="text/javascript">
        $(function() {
            $('#myDataTable').DataTable({
                processing: true,
                serverSide: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json'
                },
                ajax: '{!! route('programs.index.datatable') !!}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'mnemonic', name: 'mnemonic' },
                    { data: 'short_name', name: 'short_name' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'action', name: 'acciones' }
                ]
            });
        });
    </script>
@endpush

