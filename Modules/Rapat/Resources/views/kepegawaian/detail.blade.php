@extends('adminlte::page')
@section('title', 'Kepanitiaan')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h3 class="m-0 text-dark">Kepanitiaan</h3>
@stop

@push('css')
@endpush

@section('content')
    <x-adminlte-card>
        <div class="container-fluid">
            <h2 class="text-center mb-4">Detail Kepanitiaan</h2>

            <!-- Informasi Dasar Kepanitiaan -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ $panitia->nama_kepanitiaan }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar-alt mr-2"></i>Tanggal Mulai:</label>
                                        <p>{{ \Carbon\Carbon::parse($panitia->tanggal_mulai)->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar-check mr-2"></i>Tanggal Berakhir:</label>
                                        <p>{{ \Carbon\Carbon::parse($panitia->tanggal_berakhir)->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-clipboard-list mr-2"></i>Deskripsi:</label>
                                <p>{{ $panitia->deskripsi }}</p>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-bullseye mr-2"></i>Tujuan Kepanitiaan:</label>
                                <p>{{ $panitia->tujuan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Anggota Kepanitiaan -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-users mr-2"></i>Anggota Kepanitiaan</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="bg-light">
                                            <th width="5%">No</th>
                                            <th width="15%">NIP</th>
                                            <th width="30%">Nama</th>
                                            <th width="25%">Jabatan</th>
                                            <th width="25%">Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($panitia->pegawai as $index => $anggota)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $anggota->nip }}</td>
                                                <td>{{ $anggota->nama }}</td>
                                                <td>{{ $anggota->jabatan }}</td>
                                                <td>{{ $anggota->unit }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada anggota kepanitiaan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-adminlte-card>
@endsection

@push('js')
@endpush
