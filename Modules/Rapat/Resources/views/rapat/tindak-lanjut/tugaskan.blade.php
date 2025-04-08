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
        <form class="col-8 mx-auto" action="{{ url('/rapat/agenda-rapat/' . $rapat->slug . '/tugaskan/' . $peserta->id) }}"
            method="POST">
            @csrf
            <div class="mb-3">
                <label for="deskripsi-tugas" class="form-label">Deskripsi Tugas :</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi-tugas"
                    rows="3">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="deskripsi-date" class="form-label">Batas Waktu :</label>
                <input type="date" name="batas_waktu" class="form-control @error('batas_waktu') is-invalid @enderror"
                    id="deskripsi-date" value="{{ old('batas_waktu') }}">
                @error('batas_waktu')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Tugaskan</button>
        </form>
    </x-adminlte-card>
@endsection

@push('js')
@endpush
