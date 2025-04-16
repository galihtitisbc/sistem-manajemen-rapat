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
        @php
            \Carbon\Carbon::setLocale('id');
        @endphp
        <div class="col-8 mx-auto mt-4">
            <div class="row">
                <div class="col">
                    <p><strong>Deskripsi Tugas :</strong></p>
                    <p> {{ $rapatTindakLanjut->deskripsi_tugas }}</p>
                </div>
                <div class="col">
                    <p><strong>Batas Waktu Pengumpulan :</strong></p>
                    <p> {{ \Carbon\Carbon::parse($rapatTindakLanjut->batas_waktu)->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            <form action="" class="mt-4">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Tugas ( Opsional ) :</label>
                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                placeholder="Jika Tugas Berbentuk Link">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Kendala ( Jika Ada ) :</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">File Tugas ( Jika Ada ) :</label>
                    <input class="form-control" multiple type="file" id="formFile">
                </div>
                <div class="text-center">
                    <x-adminlte-button type="submit" label="Unggah Tugas" theme="primary" />
                </div>
            </form>
        </div>
    </x-adminlte-card>
@endsection

@push('js')
@endpush
