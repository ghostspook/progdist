@extends ('layouts.app')

@push('custom_styles')
{{-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> --}}
@endpush

@section ('content')



<div class="row">

    <div class="col-md-12">
        <form method="POST" action="{{ route('conflicts.virtualrooms.show') }}">
            @csrf

            <div class="ml-2 mr-2 row p-3 mb-2 bg-info text-black">
                Aquí puede consultar posibles cruces de aulas virtuales. Se muestran sesiones que tengan 15 minutos ( o menos ) de separación entre ellas para alertar de posibles cruces.
            </div>

            <div class="row">
                <div class="col-md-2 ml-2">
                    <input type="date" name="booking_date" value={{$booking_date ?? ''}} class="form-control" />

                </div>
                <div class="col-md-4">
                    <button class="btn btn-success" type="submit"><i class="fa fa-save"></i>Consultar</button>
                </div>
            </div>
            <div class="row mt-4">
                {{-- <input type="hidden" name="id" value="{{ $i->id }}" /> --}}
                <div class="col-md-12">
                    <table class="table">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">Día</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Programa</th>
                            <th scope="col">Aula Virtual</th>
                            <th scope="col">Inicia</th>
                            <th scope="col">Termina</th>
                          </tr>
                        </thead>
                        <tbody>
                            @isset($virtualRoomConflicts)
                                @foreach ( $virtualRoomConflicts as $conflict )
                                    <tr>
                                        <th>  {{Carbon\Carbon::parse($conflict->booking_date)->locale('es_ES')->dayName }} </th>
                                        <th>  {{Carbon\Carbon::parse($conflict->booking_date)->locale('es_ES')->isoFormat('D-MMMM-Y')}} </th>
                                        <th>  {{$conflict->program}} </th>
                                        <th>  {{$conflict->virtualRoom}} </th>
                                        <th>  {{Carbon\Carbon::parse($conflict->start_time)->locale('es_ES')->isoFormat('HH:mm')}} </th>
                                        <th>  {{Carbon\Carbon::parse($conflict->end_time)->locale('es_ES')->isoFormat('HH:mm')}} </th>



                                    </tr>
                                @endforeach
                                {{ $virtualRoomConflicts->links()}}
                            @endisset



                        </tbody>
                      </table>




                </div>
            </div>


        </form>

    </div>
</div>

@endsection

@push('js')

@endpush

