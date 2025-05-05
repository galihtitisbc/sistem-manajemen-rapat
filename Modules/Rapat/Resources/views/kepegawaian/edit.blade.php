@extends('adminlte::page')
@section('title', 'Kepanitiaan')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h3 class="m-0 text-dark">Buat Kepanitiaan</h3>
@stop

@push('css')
@endpush

@section('content')

    <x-adminlte-card>
        <div class="col-lg-8 col-sm-12 col-md-12 mx-auto">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ url('/rapat/panitia/' . $kepanitiaan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Nama Kepanitiaan</label>
                    <input type="text" name="nama_kepanitiaan" class="form-control"
                        value="{{ old('nama_kepanitiaan', $kepanitiaan->nama_kepanitiaan ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi', $kepanitiaan->deskripsi ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                        value="{{ old('tanggal_mulai', $kepanitiaan->tanggal_mulai ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal Berakhir</label>
                    <input type="date" name="tanggal_berakhir" class="form-control"
                        value="{{ old('tanggal_berakhir', $kepanitiaan->tanggal_berakhir ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Tujuan</label>
                    <input type="text" name="tujuan" class="form-control"
                        value="{{ old('tujuan', $kepanitiaan->tujuan ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label>Peserta</label>
                    {{-- <input type="checkbox" name="pegawai_username[]" value="{{ $pegawai->username }}"
                        @if (isset($selectedUsers) && in_array($pegawai->username, $selectedUsers)) checked @endif> --}}
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </x-adminlte-card>
@endsection

@push('js')
    <script></script>
@endpush
