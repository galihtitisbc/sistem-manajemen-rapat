@extends('adminlte::page')
@section('title', 'Kepanitiaan')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h3 class="m-0 text-dark">Buat Kepanitiaan</h3>
@stop

@push('css')
    @livewireStyles
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
            <form action="{{ url('/rapat/panitia') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Kepanitiaan</label>
                    <input type="text" name="nama_kepanitiaan" class="form-control" value="{{ old('nama_kepanitiaan') }}">
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal Berakhir</label>
                    <input type="date" name="tanggal_berakhir" class="form-control"
                        value="{{ old('tanggal_berakhir') }}">
                </div>

                <div class="mb-3">
                    <label>Tujuan</label>
                    <input type="text" name="tujuan" class="form-control" value="{{ old('tujuan') }}">
                </div>
                <div class="mb-3">
                    <label>Peserta</label>
                    <x-adminlte-datatable id="usersTable" :heads="['Nama', 'Pilih']">
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                        @if (in_array($user->id, old('user_ids', $selectedUsers ?? []))) checked @endif>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </x-adminlte-card>
@endsection

@push('js')
@endpush
