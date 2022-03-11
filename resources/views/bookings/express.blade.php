@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div id="app">
    {{-- @if(Auth::user()->authorizedAccount->can_create_and_edit_bookings) --}}
        <session-express></session-express>
    {{-- @endif --}}

</div>
@endsection

