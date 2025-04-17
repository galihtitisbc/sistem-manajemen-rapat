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
                $belumSelesaiEnum = \Modules\Rapat\Http\Helper\StatusTindakLanjut::BELUM_SELESAI->value;
                $selesaiEnum = \Modules\Rapat\Http\Helper\StatusTindakLanjut::SELESAI->value;
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

                $heads = [
                    ['label' => 'No', 'width' => 5, 'class' => 'text-center'],
                    ['label' => 'Nama Peserta', 'width' => 10],
                    ['label' => 'Tugas', 'width' => 10],
                    ['label' => 'Target Penyelesaian', 'width' => 10],
                    ['label' => 'Status', 'width' => 10, 'class' => 'text-center'],
                    ['label' => 'Aksi', 'width' => 10, 'class' => 'text-center'],
                ];
                $data = [];
                $status = '';
                $aksi = '';
                foreach ($tindakLanjuts as $key => $tindakLanjut) {
                    $status =
                        '<span class="badge bg-' .
                        $statusTindakLanjut[$tindakLanjut->status] .
                        '">' .
                        $tindakLanjut->status .
                        '</span>';
                    $btnDetail = '<button class="btn btn-success mx-2" data-toggle="modal" data-target="#exampleModal"> <i class="fas fa-eye" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Detail Rapat"></i></button>';
                    $btnUpdate =
                        '<a href="' .
                        url('/rapat/tindak-lanjut-rapat/tugas/' . $tindakLanjut->slug . '/ubah-tugas') .
                        '" class="btn btn-warning"> <i class="fas fa-edit" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Ubah Tugas"></i></a>';
                    $aksi = $btnDetail;
                    if ($tindakLanjut->user_id == Auth::user()->id) {
                        $aksi .= $btnUpdate;
                    }
                    if ($tindakLanjut->status == $belumSelesaiEnum) {
                        $aksi = '-';
                    }
                    $data[] = [
                        $key + 1,
                        $tindakLanjut->user->name,
                        $tindakLanjut->deskripsi_tugas,
                        $tindakLanjut->batas_waktu,
                        $status,
                        $aksi,
                    ];
                }
                $config = [
                    'data' => $data,
                    'order' => [[1, 'asc']],
                    'columns' => [
                        ['className' => 'text-center'],
                        null,
                        ['orderable' => false],
                        null,
                        ['className' => 'text-center'],
                        ['className' => 'text-center', 'orderable' => false],
                    ],
                ];
            @endphp
            <x-adminlte-card>
                <div class="col-11 mx-auto mt-4">
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-adminlte-datatable id="detail-tindak-lanjut" :heads="$heads" :config="$config">
                        @foreach ($config['data'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@push('js')
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
