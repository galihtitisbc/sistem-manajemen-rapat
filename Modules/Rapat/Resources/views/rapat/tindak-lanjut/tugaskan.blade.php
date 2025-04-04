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
        <div class="row col-10 mx-auto mt-5">
            <div class="col-lg-2 col-sm-4 col-md-4">
                <span style="font-weight: bold">Nama Peserta :</span>
                <hr>
            </div>
            <div class="col-8">
                <p>{{ $peserta->name }}</p>
                <hr>
            </div>
        </div>
        <form class="col-8 mx-auto" action="" method="POST">
            @csrf
            <div class="mb-3">
                <label for="deskripsi-tugas" class="form-label">Deskripsi Tugas :</label>
                <textarea class="form-control" id="deskripsi-tugas" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="deskripsi-date" class="form-label">Batas Waktu :</label>
                <input type="date" class="form-control" id="deskripsi-date">
            </div>
            <button type="submit" class="btn btn-primary">Tugaskan</button>
        </form>
    </x-adminlte-card>
@endsection

@push('js')
@endpush
