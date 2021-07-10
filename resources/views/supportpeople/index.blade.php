@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
    {{-- @if (Auth::user()->authorizedAccount->can_create_and_edit_bookings)
        <a class="btn btn-success mt-3 mb-4 ml-2" href="{{ route('programs.create') }}"><i class="fa fa-plus"></i> Nuevo</a>
    @endif --}}


    <div id="app">
        <support-people

        >

        </support-people>

    </div>
@endsection

@push('js')

@endpush

