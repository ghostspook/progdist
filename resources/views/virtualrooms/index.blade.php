@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
        <div id="app">
            <virtual-room> </virtual-room>
        </div>
@endsection

@push('js')
    <script type="text/javascript">

    </script>
@endpush

