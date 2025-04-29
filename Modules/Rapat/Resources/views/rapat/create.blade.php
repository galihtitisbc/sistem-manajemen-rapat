@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h3 class="m-0 text-dark">Buat Agenda Rapat</h3>
@stop

@push('css')
@endpush

@section('content')

    <x-adminlte-card>
        <div class="d-flex justify-content-center">
            {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
            <form method="POST" class="col-lg-8 col-md-6 col-sm-10" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nomor-surat" class="form-label">Nomor Surat Undangan:</label>
                    <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" id="nomor-surat">
                    @error('nomor_surat')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="waktu-mulai" class="form-label">Waktu Mulai :</label>
                            <input type="datetime-local" class="form-control @error('waktu_mulai') is-invalid @enderror"
                                id="waktu-mulai">
                            @error('waktu_mulai')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="waktu-selesai" class="form-label">Waktu Selesai :</label>
                            <select id="pilihan-waktu-selesai" class="form-control mb-2">
                                <option value="">-- Pilih --</option>
                                <option value="manual">Masukkan Tanggal</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                        <input type="datetime-local" class="form-control @error('waktu_selesai') is-invalid @enderror"
                            id="waktu-selesai">
                        @error('waktu_selesai')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="tempat" class="form-label">Tempat Rapat</label>
                    <select id="pilihan-tempat" class="form-control @error('tempat') is-invalid @enderror">
                        <option selected value="">-- Pilih Tempat --</option>
                        <option value="zoom">Online</option>
                        <option value="custom">Tempat Lain</option>
                    </select>
                    @error('tempat')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    {{-- @if ($selectTempat === 'custom') --}}
                    <div class="mt-3" id="tempat-rapat-group">
                        <label for="tempat-rapat" class="form-label">Masukkan Tempat Rapat</label>
                        <input type="text" id="tempat-rapat" class="form-control @error('tempat') is-invalid @enderror"
                            placeholder="Masukkan Tempat Rapat">
                    </div>
                    @error('tempat')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    {{-- @endif --}}
                </div>
                <div class="mb-3">
                    <label>Agenda Rapat :</label>
                    <textarea class="form-control @error('agenda_rapat') is-invalid @enderror" placeholder="Agenda Rapat"></textarea>
                    @error('agenda_rapat')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label>Pilih Kepanitiaan : ( Jika Rapat Merupakan Rapat Kepanitiaan )</label>
                    <select class="form-control @error('kepanitiaan') is-invalid @enderror">
                        <option value="">-- Pilih Kepanitiaan --</option>
                        {{-- @foreach ($kepanitiaans as $kepanitiaan)
                            <option value="{{ $kepanitiaan->id }}">{{ $kepanitiaan->nama_kepanitiaan }}
                            </option>
                        @endforeach --}}
                    </select>
                    @error('kepanitiaan')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 my-4">
                    <div class="my-4" id="peserta-rapat">
                        <div class="d-flex justify-content-between mb-4">
                            <label>Pilih Peserta Rapat :</label>
                        </div>
                        <table id="table-peserta-rapat" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Peserta</th>
                                    <th scope="col">Whatsapp</th>
                                    <th scope="col">Pilih</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label>Lampiran : ( Jika Ada )</label>
                        <input type="file" class="form-control" id="lampiran-file" multiple>
                        @error('lampiran.*')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror

                        {{-- File Preview --}}
                        {{-- @if ($lampiran)
                        <div class="mt-3">
                            @foreach ($lampiran as $item)
                                @php
                                    $extension = strtolower($item->getClientOriginalExtension());
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
                                    $fileData = $icons[$extension] ?? ['icon' => 'fas fa-file', 'color' => '#A9A9A9'];
                                @endphp
                                <i class="{{ $fileData['icon'] }}"
                                    style="color: {{ $fileData['color'] }}; fa-lg  mr-2"></i>
                                {{ $item->getClientOriginalName() }}
                                <br>
                            @endforeach
                        </div>
                    @endif --}}
                    </div>
                    <div class="my-4">
                        <label>Pilih Pimpinan Rapat :</label>
                        <table id="table-pimpinan-rapat" class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Peserta</th>
                                    <th scope="col">Whatsapp</th>
                                    <th scope="col">Undang</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        @error('pimpinan_id')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 my-4">
                        <label>Pilih Notulis Rapat :</label>
                        <div style="max-height: 300px; overflow-y: scroll;">
                        </div>
                        @error('notulis_id')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mx-auto">Submit</button>
                    </div>
            </form>
        </div>
    </x-adminlte-card>
@endsection

@push('js')
    <script src="{{ asset('assets/js/rapat/createRapat.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener("swal", (event) => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon
                });
            });
        });
    </script>
@endpush
