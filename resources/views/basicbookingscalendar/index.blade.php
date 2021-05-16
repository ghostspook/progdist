@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div id="app">
    <basic-bookings-calendar>  </basic-bookings-calendar>
</div>


@endsection
