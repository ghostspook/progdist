@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div id="app">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">
                    Profesores y Áreas
                </h5>
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-md-7">
                            <instructor> </instructor>
                        </div>
                        <div class="col-md-5">
                            <areas-for-instructor></areas-for-instructor>
                        </div>
                    </div> --}}

                    <instructor-area-view></instructor-area-view>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush

