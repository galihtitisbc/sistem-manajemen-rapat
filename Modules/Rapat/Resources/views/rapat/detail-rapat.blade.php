@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
@endpush

@section('content')
    @php
        \Carbon\Carbon::setLocale('id');
        $statusPeserta = [
            'BERSEDIA' => 'primary',
            'TIDAK_BERSEDIA' => 'danger',
            'HADIR' => 'success',
            'TIDAK_HADIR' => 'secondary',
            'MENUNGGU' => 'warning',
        ];
        $statusRapat = [
            'CANCELED' => ['danger', 'Di Batalkan'],
            'SCHEDULED' => ['warning', 'Di Jadwalkan'],
            'COMPLETED' => ['success', 'Selesai'],
            'STARTED' => ['primary', 'Sedang Berlangsung'],
        ];
        $icons = [
            'jpg' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
            'jpeg' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
            'png' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
            'PNG' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
            'doc' => ['icon' => 'fas fa-file-word', 'color' => '#1E90FF'],
            'docx' => ['icon' => 'fas fa-file-word', 'color' => '#1E90FF'],
            'xls' => ['icon' => 'fas fa-file-excel', 'color' => '#008000'],
            'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => '#008000'],
            'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => '#FF0000'],
            'txt' => ['icon' => 'fas fa-file-alt', 'color' => '#808080'],
        ];
    @endphp
    <div class="card">
        <x-adminlte-card>
            <h4 class="text-center mb-4">{{ $rapat->agenda_rapat }}</h4>
            <x-adminlte-card>
                <div class="row d-flex justify-content-between">
                    <div class="col-lg-4 col-sm-12 col-md-12">
                        <b class="text-primary mb-2">Status</b>
                        <div class="p-2 mb-1 bg-{{ $statusRapat[$rapat->status][0] }} text-center">
                            {{ $statusRapat[$rapat->status][1] }}
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-md-12">
                        <b class="text-primary mb-2">Waktu</b>
                        <div class="p-2 mb-1 bg-primary text-center">
                            {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->translatedFormat('l, d F Y H:i') }}
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-md-12">
                        <b class="text-primary mb-2">Tempat</b>
                        <div class="p-2 mb-1 bg-secondary text-center">{{ $rapat->tempat }}</div>
                    </div>
                </div>
            </x-adminlte-card>

            <div class="row m-4">
                @if ($rapat->tempat == 'zoom')
                    <div class="col-4">
                        <span style="font-weight: bold">Link Zoom</span>
                        <hr>
                    </div>
                    <div class="col-8">
                        <span><a href="{{ $rapat->zoom_link }}" target="_blank">{{ $rapat->zoom_link }}</a></span>
                        <hr>
                    </div>
                @endif
                <div class="col-4">
                    <span style="font-weight: bold">Link Google Calendar</span>
                    <hr>
                </div>
                <div class="col-8">
                    <span><a href="{{ $rapat->calendar_link }}" target="_blank">Google Calendar</a></span>
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
                                @php
                                    $extension = pathinfo($lampiran->nama_file, PATHINFO_EXTENSION);
                                    $icon = $icons[$extension] ?? [
                                        'icon' => 'fas fa-file',
                                        'color' => '#A9A9A9',
                                    ];
                                @endphp
                                <div class="col-12">
                                    <span class="mr-2">
                                        <a href="{{ url('/rapat/agenda-rapat/' . $lampiran->nama_file . '/download') }}"
                                            target="_blank"><i class="{{ $icon['icon'] }}"
                                                style="color: {{ $icon['color'] }}; fa-lg  mr-2"></i>
                                            {{ $lampiran->nama_file }}</a>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="text-danger">Tidak ada lampiran</span>
                    @endif
                    <hr>
                </div>
                <div class="col-4">
                    <span style="font-weight: bold">Pimpinan Rapat :</span>
                    <hr>
                </div>
                <div class="col-8">
                    <span style="font-weight: bold">{{ $rapat->rapatAgendaPimpinan->nama }}</span>
                    <hr>
                </div>
                <div class="col-4">
                    <span style="font-weight: bold">Notulis Rapat :</span>
                    <hr>
                </div>
                <div class="col-8">
                    <span style="font-weight: bold">{{ $rapat->rapatAgendaNotulis->nama }}</span>
                    <hr>
                </div>
            </div>
            <div class="mt-5 col-11 mx-auto">
                <h5>Daftar Peserta :</h5>
                <table id="daftar-peserta" class="table table-hover text-center">
                    <thead class="text-center">
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Whatsapp</th>
                        <th class="text-center">Status</th>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($rapat->rapatAgendaPeserta as $peserta)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $peserta->nama }}</td>
                                <td class="text-center">082232123</td>
                                <td class="text-center"><span
                                        class="badge bg-{{ $statusPeserta[$peserta->pivot->status] }}">{{ \Modules\Rapat\Http\Helper\StatusPesertaRapat::from($peserta->pivot->status)->label() }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </x-adminlte-card>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            new DataTable('#daftar-peserta', {
                responsive: true,
            });
        })
    </script>
@endpush
