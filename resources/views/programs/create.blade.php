@extends ('layouts.app')


@push('custom_styles')
    <link rel="stylesheet"  type="text/css" href="{{ asset('css/custom.css') }}" >

@endpush

@php
    $colors= [ 'Azul' => 'blue', 'Rojo' => 'red',  'Naranja' =>'orange', 'Verde' =>'green',
        'Naranja Oscuro' =>'dark_orange', 'Verde Oscuro' =>'dark_green',
        'Rojo Oscuro' =>'dark_red', 'Azul Oscuro' => 'dark_blue',
        'Fucsia' => 'fucsia', 'Vino' =>'wine', 'Púpura' => 'purple',
        'Mamey' =>'mamey', 'Very Peri' =>'very_peri', 'Amarillo Pokemon' =>'pokemon_yellow',
        'Azul Pokemon' => 'pokemon_blue', 'Rojo Pokemon' => 'pokemon_red',
        'Mostaza Pokemon' =>'pokemon_mustard', 'Pistacho' => 'pistachio',
        ];
@endphp

@section ('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <h5 class="card-header">
                Nuevo Programa
            </h5>
            <div class="card-body">
                <form method="POST" action="{{ route('programs.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="@error('name') text-danger @enderror">Nombre</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mnemonic" class="@error('mnemonic') text-danger @enderror">Mnemónico</label>
                                <input type="text" id="mnemonic" name="mnemonic" class="form-control @error('mnemonic') is-invalid @enderror" value="{{ old('mnemonic') }}" required>
                                @error('mnemonic')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="short_name"  class="@error('short_name') text-danger @enderror">Nombre corto</label>
                                <input type="text" id="short_name" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name') }}" required>
                                @error('short_name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date" class="@error('start_date') text-danger @enderror">Fecha inicio</label>
                                <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date" class="@error('end_date') text-danger @enderror">Fecha fin</label>
                                <input type="date" id="end_date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="class">Color</label>
                                <select name="class" id="class" class="form-control" value="">
                                    <option value="">Ninguno</option>
                                    <option value="blue">Azul</option>
                                    <option value="red">Rojo</option>
                                    <option value="orange">Naranja</option>
                                    <option value="green">Verde</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div  class="form-group">
                                <label  for="class">Color</label>
                                <select name="class" id="class" class="form-control" value="">
                                    <option value="" >Ninguno</option>

                                    @foreach ( $colors as $color )
                                        <option class="{{ 'vuecal__event' . ' ' . $color }}" value="{{ $color}}">
                                            {{ array_search ($color, $colors)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="is_visible">Visible</label>
                                <select name="is_visible" id="is_visible" class="form-control" value="1">
                                    <option value="0" >NO</option>
                                    <option value="1" selected>Sí</option>

                                </select>
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
