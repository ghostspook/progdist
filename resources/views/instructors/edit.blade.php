@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}

@endpush

@section ('content')




    <div  class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">
                    Datos del Profesor
                </h5>
                <div class="card-body">
                    <form method="POST" action="{{ route('instructors.update', [ 'id' => $i->id ]) }}">
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
        <div class="col-md-6">
            <div class="card">
                <h5 class="card-header">
                    Áreas de este Profesor
                </h5>
                <div class="card-body">

                    <form method="POST" action="{{ route('instructorareas.store', [ 'id' => $i->id ]) }}">
                        @csrf

                        <div class="row">
                            <input type="hidden" name="id" value="{{ $i->id }}" />
                            <div class="col-md-12">

                                    <ul>
                                        @foreach ( $instructor_areas as $ia )
                                                <li>
                                                    {{$ia->area_mnemonic}} --  {{$ia->area_name}}
                                                </li>
                                        @endforeach
                                    </ul>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-8 form-group">

                                <select class="form-control form-select form-select-lg mb-3 @error('area_id') is-invalid @enderror" aria-label="Default select example" name="area_id" >
                                    @foreach ( $areas as $area )
                                        <option value="{{$area->id}}"> {{$area->mnemonic}} --- {{$area->name}}</option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Añadir Área</button>
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
                    Bloqueos del Profesor
                </h5>
                <div class="card-body">
                    <form method="POST" action="{{ route('instructorconstraints.store', [ 'id' => $i->id ]) }}">
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
                                                <form method="POST" action="{{ route('instructorconstraints.destroy', [ 'id' => $constraint->id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="delete" />
                                                    <input type="hidden" name="instructor_id" value={{$i->id}} />
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
