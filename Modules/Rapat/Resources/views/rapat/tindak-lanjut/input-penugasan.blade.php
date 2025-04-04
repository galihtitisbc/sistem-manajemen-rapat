@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark"></h1>
@stop

@push('css')
@endpush

@section('content')
    <x-adminlte-card>
        <h4 class="text-center mb-4">{{ $rapat->agenda_rapat }}</h4>
        <div class="col-9 mx-auto mt-5">
            <h6 class="mb-4">Peserta Rapat :</h6>
            @foreach ($rapat->rapatAgendaPeserta as $peserta)
                <div class="daftar-peserta col-11 mx-auto">
                    <div class="peserta d-flex justify-content-between align-items-center ">
                        <p style="font-size: 1.2rem;">{{ $peserta->name }}</p>
                        @if ($peserta->pivot->is_penugasan == false)
                            <a href="{{ url('/rapat/agenda-rapat/' . $rapat->slug . '/tugaskan/' . $peserta->id) }}"
                                class="btn btn-primary">Tugaskan</a>
                        @else
                            <button class="btn btn-danger">Sudah Ditugaskan</button>
                        @endif
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
    </x-adminlte-card>
@endsection

@push('js')
@endpush
