@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark"></h1>
@stop

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            @php
                $statusTindakLanjut = [
                    'SELESAI' => 'success',
                    'BELUM SELESAI' => 'danger',
                ];
                $icons = [
                    'jpg' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
                    'jpeg' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
                    'png' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
                    'doc' => ['icon' => 'fas fa-file-word', 'color' => '#1E90FF'],
                    'docx' => ['icon' => 'fas fa-file-word', 'color' => '#1E90FF'],
                    'xls' => ['icon' => 'fas fa-file-excel', 'color' => '#008000'],
                    'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => '#008000'],
                    'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => '#FF0000'],
                    'txt' => ['icon' => 'fas fa-file-alt', 'color' => '#808080'],
                ];
            @endphp
            <x-adminlte-card>
                <h4 class="text-center my-4">{{ $rapat->agenda_rapat }}</h4>
                <div class="col-sm-12 col-lg-6 mx-auto my-4">
                    @foreach ($tindakLanjuts as $tindakLanjut)
                        <x-adminlte-card theme="primary" theme-mode="outline">
                            <div class="d-flex justify-content-start">
                                <i class="fas fa-tasks fa-lg" style="color: #74C0FC;"></i>
                                <p class="ml-3" style="font-size: 1.5rem;margin-top: -1%">
                                    {{ $tindakLanjut->deskripsi_tugas }}</p>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-start">
                                        <i class="fas fa-user fa-lg" style="color: #74C0FC;"></i>
                                        <p class="ml-3" style="font-size: 1.2rem;margin-top: -1%">Peserta Yang Ditugaskan
                                            :
                                        </p>
                                    </div>
                                    <p class="ml-5" style="font-size: 1.1rem;">{{ $tindakLanjut->user->name }}</p>
                                </div>
                                <div class="col">
                                    <p class="ml-3" style="font-size: 1.2rem;margin-top: -1%">Batas Waktu Pengumpulan :
                                    </p>
                                    <div class="d-flex justify-content-start ml-3">
                                        <i class="fas fa-calendar-alt fa-lg" style="color: #74C0FC;"></i>
                                        <p class="ml-2" style="font-size: 1.1rem;">{{ $tindakLanjut->batas_waktu }}</p>
                                    </div>
                                </div>
                            </div>
                            <p style="font-size: 1.2rem;">Berkas Pengumpulan :</p>
                            @if ($tindakLanjut->status == 'BELUM SELESAI')
                                <span class="badge badge badge-danger p-2">Belum Selesai</span>
                            @endif
                            @if ($tindakLanjut->status == 'SELESAI')
                                <x-adminlte-callout theme="success">
                                    <p>{{ $tindakLanjut->tugas }}</p>
                                    @foreach ($tindakLanjut->rapatTindakLanjutFile as $file)
                                        @php
                                            $extension = pathinfo($file->nama_file, PATHINFO_EXTENSION);
                                            $icon = $icons[$extension] ?? [
                                                'icon' => 'fas fa-file',
                                                'color' => '#A9A9A9',
                                            ];
                                        @endphp
                                        <i class="{{ $icon['icon'] }}" style="color: {{ $icon['color'] }}; fa-lg  mr-2"></i>
                                        {{ $file->nama_file }}
                                    @endforeach
                                </x-adminlte-callout>
                            @endif
                        </x-adminlte-card>
                    @endforeach
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@push('js')
    <script></script>
@endpush
