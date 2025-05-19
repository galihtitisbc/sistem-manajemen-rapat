@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark">List Agenda Rapat</h1>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
@endpush

@section('content')
    <x-adminlte-card>
        @if (Auth::user()->hasAnyRole(['pimpinan', 'pejabat', 'sekretaris']) ||
                Auth::user()->pegawai->ketuaPanitia->isNotEmpty())
            <div class="btn-tambah d-flex justify-content-end my-2">
                <a href="{{ url('rapat/agenda-rapat/create') }}" class="btn btn-primary">Tambah Rapat</a>
            </div>
        @endif

        <x-adminlte-datatable id="agenda-rapat" :heads="$heads" :config="$config">
            @foreach ($config['data'] as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
@endsection

@push('js')

    <script>
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
