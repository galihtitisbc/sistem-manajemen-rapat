@extends('adminlte::page')
@section('title', 'Detail Tindak Lanjut Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h5 class="m-0 text-dark">Detail Tindak Lanjut {{ $rapat->agenda_rapat }}</h5>
@stop

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            @php
                use Modules\Rapat\Http\Helper\KriteriaPenilaian;
                use Modules\Rapat\Http\Helper\StatusTindakLanjut;
                $belumSelesaiEnum = StatusTindakLanjut::BELUM_SELESAI->value;
                $selesaiEnum = StatusTindakLanjut::SELESAI->value;
                $statusTindakLanjut = [
                    'SELESAI' => 'success',
                    'BELUM_SELESAI' => 'danger',
                ];
                $statusPenilaian = [
                    'BELUM_DINILAI' => 'secondary',
                    'MELEBIHI_EKSPETASI' => 'success',
                    'SESUAI_EKSPETASI' => 'primary',
                    'TIDAK_SESUAI_EKSPETASI' => 'danger',
                ];
                $heads = [
                    ['label' => 'No', 'width' => 5, 'class' => 'text-center'],
                    ['label' => 'Nama Peserta', 'width' => 10],
                    ['label' => 'Tugas', 'width' => 10],
                    ['label' => 'Target Penyelesaian', 'width' => 10],
                    ['label' => 'Tanggal Selesai', 'width' => 10],
                    ['label' => 'Status', 'width' => 10, 'class' => 'text-center'],
                    ['label' => 'Status Penilaian', 'width' => 10, 'class' => 'text-center'],
                    ['label' => 'Aksi', 'width' => 10, 'class' => 'text-center'],
                ];
                $data = [];
                $status = '';
                $aksi = '';
                $penilaian = '';
                foreach ($tindakLanjuts as $key => $tindakLanjut) {
                    $status =
                        '<span class="badge bg-' .
                        $statusTindakLanjut[$tindakLanjut->status] .
                        '">' .
                        StatusTindakLanjut::from($tindakLanjut->status)->label() .
                        '</span>';
                    $penilaian =
                        '<span class="badge bg-' .
                        $statusPenilaian[$tindakLanjut->penilaian] .
                        '">' .
                        KriteriaPenilaian::from($tindakLanjut->penilaian)->label() .
                        '</span>';
                    $btnDetail =
                        '<button class="btn btn-success mx-2 btn-detail" data-id="' .
                        $tindakLanjut->slug .
                        '"> <i class="fas fa-eye" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Detail Rapat"></i></button>';
                    $btnUpdate =
                        '<a href="' .
                        url('/rapat/tindak-lanjut-rapat/tugas/' . $tindakLanjut->slug . '/ubah-tugas') .
                        '" class="btn btn-warning"> <i class="fas fa-edit" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Ubah Tugas"></i></a>';
                    $aksi = $btnDetail;
                    if ($tindakLanjut->status == $belumSelesaiEnum && $tindakLanjut->user_id == Auth::user()->id) {
                        $aksi =
                            '<a href="' .
                            url('/rapat/tindak-lanjut-rapat/tugas/' . $tindakLanjut->slug . '/unggah-tugas') .
                            '" class="btn btn-primary">Unggah Tugas</a>';
                    }
                    if (
                        $tindakLanjut->status == $belumSelesaiEnum &&
                        $tindakLanjut->rapatAgenda->pimpinan_id == Auth::user()->id
                    ) {
                        $aksi = '-';
                    }
                    if ($tindakLanjut->status == $selesaiEnum && $tindakLanjut->user_id == Auth::user()->id) {
                        $aksi .= $btnUpdate;
                    }
                    $data[] = [
                        $key + 1,
                        $tindakLanjut->user->name,
                        $tindakLanjut->deskripsi_tugas,
                        $tindakLanjut->batas_waktu,
                        $tindakLanjut->tanggal_selesai ? $tindakLanjut->tanggal_selesai : '-',
                        $status,
                        $penilaian,
                        $aksi,
                    ];
                }
                $config = [
                    'data' => $data,
                    'columns' => [
                        ['className' => 'text-center'],
                        null,
                        ['orderable' => false],
                        null,
                        ['className' => 'text-center'],
                        ['className' => 'text-center'],
                        ['className' => 'text-center'],
                        ['className' => 'text-center', 'orderable' => false],
                    ],
                ];
            @endphp
            <x-adminlte-card>
                <div class="col-11 mx-auto mt-4">
                    <x-adminlte-datatable id="detail-tindak-lanjut" :heads="$heads" :config="$config">
                        @foreach ($config['data'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <!-- Modal -->
                    <div class="modal fade" id="detail-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detail Tugas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="tugas">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <strong class="me-2">Deskripsi Tugas:</strong>
                                                <span><a id="tugas-link"></a></span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="w-75">
                                                <strong class="me-2">File yang dilampirkan:</strong>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">An item</li>
                                                    <li class="list-group-item">A second item</li>
                                                    <li class="list-group-item">A third item</li>
                                                    <li class="list-group-item">A fourth item</li>
                                                    <li class="list-group-item">And a fifth one</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kendala-penugasan">Kendala Saat Mengerjakan Tugas :</label>
                                            <textarea class="form-control" id="kendala-penugasan" rows="3"></textarea>
                                        </div>
                                    </div>
                                    @if (Auth::user()->id == $rapat->pimpinan_id)
                                    @endif

                                    <form action=" {{ url('/rapat/tindak-lanjut-rapat/detail/simpan-tugas') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="komentar-penugasan">Komentar Untuk Tugas Yang Di Kirimkan :</label>
                                            <textarea class="form-control" id="komentar-penugasan" name="komentar_penugasan" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="penilaian">Pilih Kriteria Penilaian :</label>
                                            <input type="hidden" name="slug" id="slug-tugas">
                                            <select name="kriteria_penilaian" class="form-control" id="kriteria-penilaian">
                                                <option value="">Pilih Kriteria Penilaian</option>
                                                <option value="{{ KriteriaPenilaian::MELEBIHI_EKSPETASI->value }}">
                                                    {{ KriteriaPenilaian::MELEBIHI_EKSPETASI->label() }}</option>
                                                <option value="{{ KriteriaPenilaian::SESUAI_EKSPETASI->value }}">
                                                    {{ KriteriaPenilaian::SESUAI_EKSPETASI->label() }}</option>
                                                <option value="{{ KriteriaPenilaian::TIDAK_SESUAI_EKSPETASI->value }}">
                                                    {{ KriteriaPenilaian::TIDAK_SESUAI_EKSPETASI->label() }}</option>
                                            </select>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    @if (Auth::user()->id == $rapat->pimpinan_id)
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.btn-detail', function() {
            const slug = $(this).attr('data-id');

            $.ajax({
                url: `/rapat/tindak-lanjut-rapat/${slug}/detail/tugas`,
                method: 'GET',
                success: function(data) {
                    const tugasLink = data.link ?? null;
                    if (tugasLink) {
                        $('#tugas-link')
                            .attr('href', tugasLink)
                            .text(tugasLink);
                    } else {
                        $('#tugas-link')
                            .removeAttr('href')
                            .text('Tidak ada link');
                    }

                    // lampiran
                    const lampiranList = $('.tugas ul.list-group');
                    lampiranList.empty();

                    if (Array.isArray(data.rapat_tindak_lanjut_file) && data.rapat_tindak_lanjut_file
                        .length > 0) {
                        data.rapat_tindak_lanjut_file.forEach(file => {
                            const fileName = file.nama_file ?? 'File tanpa nama';
                            const fileUrl = `/storage/tindakLanjut/${fileName}`;
                            lampiranList.append(`
                    <li class="list-group-item">
                        <a href="${fileUrl}" target="_blank" download>${fileName}</a>
                    </li>
                `);
                        });
                    } else {
                        lampiranList.append(
                            '<li class="list-group-item text-muted">Tidak ada lampiran</li>');
                    }

                    // kendala
                    const kendala = data.kendala ?? '';
                    $('#kendala-penugasan').val(kendala);
                    $('#slug-tugas').val(data.slug);
                    $('#detail-modal').modal('show');
                }
            });
        });
    </script>
    @if (session('swal'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "{{ session('swal.title') }}",
                    text: "{{ session('swal.text') }}",
                    icon: "{{ session('swal.icon') }}"
                });
            });
        </script>
    @endif
@endpush
