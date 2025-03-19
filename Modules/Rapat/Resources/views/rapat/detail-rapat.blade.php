@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark"></h1>
@stop

@push('css')
@endpush

@section('content')
    @php
        $statusPeserta = [
            'BERSEDIA' => 'primary',
            'TIDAK BERSEDIA' => 'danger',
            'HADIR' => 'success',
            'TIDAK HADIR' => 'secondary',
            'MENUNGGU' => 'warning',
        ];
        $statusRapat = [
            'CANCELED' => ['danger', 'Di Batalkan'],
            'SCHEDULED' => ['warning', 'Di Jadwalkan'],
            'COMPLETED' => ['success', 'Selesai'],
            'STARTED' => ['primary', 'Sedang Berlangsung'],
        ];
    @endphp
    <div class="card">
        <div class="card-body col-lg-11 mx-auto">
            <h4 class="text-center mb-4">{{ $rapat->agenda_rapat }}</h4>
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-between">
                        <div class="col">
                            <b class="text-primary mb-2">Status</b>
                            <div class="p-2 mb-1 bg-{{ $statusRapat[$rapat->status][0] }} text-center">
                                {{ $statusRapat[$rapat->status][1] }}
                            </div>
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
            <div class="row m-4">
                <div class="col-4">
                    <span style="font-weight: bold">Link Zoom</span>
                    <hr>
                </div>
                <div class="col-8">
                    <span><a href="{{ $rapat->zoom_link }}" target="_blank">{{ $rapat->zoom_link }}</a></span>
                    <hr>
                </div>
                <div class="col-4">
                    <span style="font-weight: bold">Link Google Calendar</span>
                    <hr>
                </div>
                <div class="col-8">
                    <span>{{ $rapat->calendar_link }}</span>
                    <hr>
                </div>
                <div class="col-4">
                    <span style="font-weight: bold">Lampiran</span>
                    <hr>
                </div>
                <div class="col-8">
                    @if ($rapat->rapatLampiran->isNotEmpty())
                        <div class="row">
                            @foreach ($rapat->rapatLampiran as $lampiran)
                                <div class="col-12">
                                    <span class="mr-2">
                                        <i class="fas fa-file-pdf" style="color: #ff0000;"></i>
                                    </span>
                                    <span>{{ $lampiran->nama_file }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="text-danger">Tidak ada lampiran</span>
                    @endif
                    <hr>
                </div>
            </div>
            <div class="mt-5">
                <h5>Daftar Peserta :</h5>
                <table class="table table-hover text-center">
                    <thead class="">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($rapat->rapatAgendaPeserta as $peserta)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $peserta->name }}</td>
                                <td><span
                                        class="badge bg-{{ $statusPeserta[$peserta->pivot->status] }}">{{ $peserta->pivot->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
