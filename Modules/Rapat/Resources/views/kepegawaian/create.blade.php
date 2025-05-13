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
            <form id="formKepanitiaan" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nama Kepanitiaan</label>
                    <input type="text" id="nama_kepanitiaan" name="nama_kepanitiaan" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tanggal Berakhir</label>
                    <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tujuan</label>
                    <input type="text" id="tujuan" name="tujuan" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Peserta</label>
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
                    <label>Pimpinan Panitia :</label>
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
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </x-adminlte-card>
@endsection

@push('js')
    <script src="{{ asset('assets/js/rapat/variable.js') }}"></script>
    <script src="{{ asset('assets/js/rapat/pesertaRapatTable.js') }}"></script>
    <script src="{{ asset('assets/js/rapat/pimpinanRapatTable.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $('#formKepanitiaan').on('submit', function(e) {
            e.preventDefault();
            const data = {
                nama_kepanitiaan: $('#nama_kepanitiaan').val(),
                deskripsi: $('#deskripsi').val(),
                tanggal_mulai: $('#tanggal_mulai').val(),
                tanggal_berakhir: $('#tanggal_berakhir').val(),
                tujuan: $('#tujuan').val(),
                peserta: pesertaRapat,
                pimpinan_username: pimpinanRapatUsername
            };
            $.ajax({
                url: '/rapat/panitia', // sesuaikan route
                method: 'POST',
                data: data,
                success: function(response) {
                    console.log(response);
                    alert('Kepanitiaan berhasil disimpan!');
                    window.location.href = '/rapat/panitia';
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gagal menyimpan kepanitiaan!');
                }
            });
        });
    </script>
@endpush
