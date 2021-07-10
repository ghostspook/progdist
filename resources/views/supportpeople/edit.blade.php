@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}

@endpush

@section ('content')




    <div  class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">
                    Datos del Miembro de Soporte
                </h5>
                <div class="card-body">
                    <form method="POST" action="{{ route('supportpeople.update', [ 'id' => $i->id ]) }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $i->id }}" />
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="@error('name') text-danger @enderror">Nombre</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $i->name }}" required>
                                    @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="mnemonic" class="@error('mnemonic') text-danger @enderror">Mnemónico</label>
                                    <input type="text" id="mnemonic" name="mnemonic" class="form-control @error('mnemonic') is-invalid @enderror" value="{{ $i->mnemonic }}" required>
                                    @error('mnemonic')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Guardar</button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>


        </div>

    </div>
    <div>
    <div  class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header bg-warning">
                    Bloqueos del Miembro del Staff de Soporte
                </h5>
                <div class="card-body">
                    <form method="POST" action="{{ route('supportpeopleconstraints.store', [ 'id' => $i->id ]) }}">
                        <div class="row">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="col-md-2">
                                <label for="from" class="@error('from') text-danger @enderror">Desde</label>
                                <input type="Date" id="from" name="from" />
                            </div>
                            <div class="col-md-2">
                                <label for="to" class="@error('to') text-danger @enderror">Hasta</label>
                                <input type="Date" id="to" name="to" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Añadir Bloqueo</button>
                            </div>
                            <div>
                                @error('from')
                                    <div class="text-danger"><small>{{ $message }}</small></div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div  class="row mt-4">
        <div class="col-md-7">
                <ul>
                    @foreach ( $constraints as $constraint )
                            <li>
                                <div class="mt-2">
                                    <h5>
                                       <div class="row p-3 mb-2 bg-secondary text-white">
                                            <div class="col-md-10">
                                                <span class="bg-dark"> <strong> Del: </strong> </span>
                                                {{ Carbon\Carbon::parse($constraint->from)->locale('es_ES')->dayName }}
                                                {{ Carbon\Carbon::parse($constraint->from)->locale('es_ES')->isoFormat('D-MMMM-Y') }}

                                                <span class="bg-dark"> <strong> Al: </strong> </span>
                                                {{ Carbon\Carbon::parse($constraint->to)->locale('es_ES')->dayName }}
                                                {{ Carbon\Carbon::parse($constraint->to)->locale('es_ES')->isoFormat('D-MMMM-Y') }}
                                            </div>
                                            <div class="col-md-2">
                                                <form method="POST" action="{{ route('supportpeopleconstraints.destroy', [ 'id' => $constraint->id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="delete" />
                                                    <input type="hidden" name="support_person_id" value={{$i->id}} />
                                                    <button type="submit" class="btn btn-danger ml-2 pull-right btn-sm"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </h5>
                                </div>
                            </li>
                    @endforeach
                </ul>
        </div>
    </div>
    </div>









@endsection

@push('js')

@endpush
