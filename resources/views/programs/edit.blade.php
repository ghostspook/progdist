@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">
                Editar Programa
            </h5>
            <div class="card-body">
                <form method="POST" action="{{ route('programs.store') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}" />
                    <input type="hidden" name="_method" value="update">
                    <div class="form-group">
                        <label for="name" class="@error('name') text-danger @enderror">Nombre</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $p->name }}" required>
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mnemonic" class="@error('mnemonic') text-danger @enderror">Mnemónico</label>
                                <input type="text" id="mnemonic" name="mnemonic" class="form-control @error('mnemonic') is-invalid @enderror" value="{{ $p->mnemonic }}" required>
                                @error('mnemonic')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="short_name"  class="@error('short_name') text-danger @enderror">Nombre corto</label>
                                <input type="text" id="short_name" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ $p->short_name }}" required>
                                @error('short_name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="@error('start_date') text-danger @enderror">Fecha inicio</label>
                                <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ $p->start_date }}">
                                @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="@error('end_date') text-danger @enderror">Fecha fin</label>
                                <input type="date" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ $p->end_date }}">
                                @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
