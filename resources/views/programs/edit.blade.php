@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <h5 class="card-header">
                Datos del Programa
            </h5>
            <div class="card-body">
                <form method="POST" action="{{ route('programs.update', [ 'id' => $p->id ]) }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}" />
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $p->id }}">
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
    <div class="col-md-2">
        <div class="card">
            <h5 class="card-header">
                Links
            </h5>
            <div class="card-body">
                @foreach ($p->links as $vml)
                    <li>{{ $vml->virtualMeetingLink->link }}</li>
                @endforeach
                <form method="POST" action="{{ route('virtual_links.store')}} ">
                    @csrf
                    <input type="hidden" name="program_id" value="{{ $p->id }}" />
                    <div class="form-group">
                        <label>Aula Virtual</label>
                        <select name="virtual_room_id" class="form-control">
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->mnemonic }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="link">URL</label>
                        <input class="form-control" name="link" id="link" type="url" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" name="password" id="text" />
                    </div>
                    <div class="form-group">
                        <label for="waiting_room">Sala de Espera</label>
                        <select name="waiting_room" id="waiting_room" class="form-control">
                            <option value="0">No</option>
                            <option value="1" selected>Sí</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-link"></i> Añadir Link</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
