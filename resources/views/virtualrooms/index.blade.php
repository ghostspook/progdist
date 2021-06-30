@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
        <div id="app">
            <div class="row">
                <div class="col-md-12 ml-4 mt-4">
                    <h2>Disponibilidad de Aulas  </h2>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 ml-4 mt-2">
                    <fitting-virtual-room> </fitting-virtual-room>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 ml-4 mt-1">
                    <virtual-room> </virtual-room>
                </div>
            </div>

        </div>
@endsection

@push('js')
    <script type="text/javascript">

    </script>
@endpush

