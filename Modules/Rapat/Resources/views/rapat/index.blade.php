@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark"></h1>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
@endpush

@section('content')
    @php
        use Carbon\Carbon;
        Carbon::setLocale('id');
        $statusRapat = [
            'CANCELED' => ['danger', 'Di Batalkan'],
            'SCHEDULED' => ['warning', 'Di Jadwalkan'],
            'COMPLETED' => ['success', 'Selesai'],
            'STARTED' => ['primary', 'Sedang Berlangsung'],
        ];
        $statusKeaktifan = [
            'SCHEDULED' => ['fa-calendar-times', '#ff0000'],
            'CANCELED' => ['fa-undo', '#5cb85c'],
            'COMPLETED' => ['fas fa-check-circle', '#28a745'],
            'STARTED' => ['fas fa-play-circle', '#0275d8'],
        ];
    @endphp
    <x-adminlte-card>
        @hasanyrole(['pimpinan', 'pejabat', 'sekretaris'])
            <div class="btn-tambah d-flex justify-content-end my-2">
                <a href="{{ url('rapat/agenda-rapat/create') }}" class="btn btn-primary">Tambah Rapat</a>
            </div>
        @endhasanyrole

        <table class="table table-striped mx-auto" id="agenda-rapat">
            <thead class="text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Topik Rapat</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                    <th scope="col">Tugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rapats as $rapat)
                    @php
                        $startTime = Carbon::parse($rapat->waktu_mulai);
                        $endTime = Carbon::parse($rapat->waktu_selesai);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-wrap" style="max-width: 200px; word-wrap: break-word;">
                            {{ $rapat->agenda_rapat }}</td>
                        <td class="text-center"> {{ $startTime->translatedFormat('l, d F Y, H:i') }} WIB</td>
                        <td class="text-center">
                            <span
                                class="badge bg-{{ $statusRapat[$rapat->status][0] }}">{{ $statusRapat[$rapat->status][1] }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('rapat/agenda-rapat/' . $rapat->slug . '/detail') }}">
                                <i class="fas fa-eye fa-lg" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Detail Rapat"></i>
                            </a>
                            @if ($rapat->user_id == Auth::user()->id || $rapat->pimpinan_id == Auth::user()->id)
                                <a href="{{ url('rapat/agenda-rapat/' . $rapat->slug . '/edit') }}" class="mx-2 my-2">
                                    <i class="fas fa-edit fa-lg" style="color: #FFD43B;" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Edit Rapat"></i>
                                </a>
                                <a
                                    @if ($rapat->status == 'CANCELED' || $rapat->status == 'SCHEDULED') href="{{ url('rapat/agenda-rapat/' . $rapat->slug . '/batal') }}"
                                                onclick="return batalkanRapat(event,this.href,'{{ $rapat->status }}')" @endif>
                                    <i class="fas {{ $statusKeaktifan[$rapat->status][0] }} fa-lg"
                                        style="color: {{ $statusKeaktifan[$rapat->status][1] }};" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="{{ $rapat->status == \Modules\Rapat\Http\Helper\StatusAgendaRapat::SCHEDULED->value ? 'Batalkan Rapat' : 'Jadwalkan Kembali' }}"></i>
                                </a>
                            @endif
                            @if ($rapat->notulis_id == Auth::user()->id && $rapat->status != 'CANCELED')
                                <a href="#" class="btn btn-success">Isi Notulen</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (
                                $rapat->notulis_id == Auth::user()->id ||
                                    $rapat->user_id == Auth::user()->id ||
                                    $rapat->pimpinan_id == Auth::user()->id)
                                @if ($rapat->status == 'COMPLETED' || $rapat->status == 'STARTED')
                                    <a href="{{ url('rapat/agenda-rapat/' . $rapat->slug . '/tugas') }}"
                                        class="btn btn-primary"> Isi Penugasan</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>
@endsection

@push('js')

    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $('#agenda-rapat').DataTable({
            select: true,
            responsive: true,
            columnDefs: [{
                targets: [4, 5],
                orderable: false,
                className: 'text-center'
            }, {
                targets: [0, 2, 3, 4],
                className: 'text-center'
            }]
        });
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl =>
            new bootstrap.Tooltip(tooltipTriggerEl)
        );

        function batalkanRapat(event, url, status) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: status == "SCHEDULED" ? "Rapat akan dibatalkan!" : "Rapat akan dijadwalkan kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: status == "SCHEDULED" ? 'Ya, Batalkan Rapat!' : 'Ya, Jadwalkan Kembali!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
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
