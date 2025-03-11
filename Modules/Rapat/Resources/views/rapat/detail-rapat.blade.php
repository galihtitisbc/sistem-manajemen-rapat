@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark"></h1>
@stop

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-body col-lg-8 mx-auto">
            <h4 class="text-center mb-4">{{ $rapat->agenda_rapat }}</h4>
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-between">
                        <div class="col">
                            <b class="text-primary mb-2">Status</b>
                            <div class="p-2 mb-1 bg-warning text-center">{{ $rapat->status }}</div>
                        </div>
                        <div class="col">
                            <b class="text-primary mb-2">Waktu</b>
                            <div class="p-2 mb-1 bg-primary text-center">
                                {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->translatedFormat('l, d F Y') }}
                            </div>
                        </div>
                        <div class="col">
                            <b class="text-primary mb-2">Tempat</b>
                            <div class="p-2 mb-1 bg-secondary text-center">{{ $rapat->tempat }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td class="fw-bold">Tempat</td>
                            <td>:</td>
                            <td>{{ $rapat->tempat }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Agenda Rapat</td>
                            <td>:</td>
                            <td>{{ $rapat->agenda_rapat }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Lampiran</td>
                            <td>:</td>
                            @if ($rapat->rapatLampiran->isNotEmpty())
                            @endif
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
@endsection

@push('js')
@endpush
