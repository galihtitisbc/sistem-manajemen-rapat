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
            <div class="card">
                <div class="card-body">
                    @hasanyrole(['pimpinan', 'pejabat', 'sekretaris'])
                        <div class="btn-tambah d-flex justify-content-end my-2">
                            <a href="{{ url('rapat/agenda-rapat/create') }}" class="btn btn-primary">Tambah Rapat</a>
                        </div>
                    @endhasanyrole
                    <table class="table table-striped">
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
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-wrap" style="max-width: 200px; word-wrap: break-word;">
                                        {{ $rapat->agenda_rapat }}</td>
                                    <td class="text-center">{{ $rapat->waktu_mulai }} - {{ $rapat->waktu_selesai }}</td>
                                    <td class="text-center">
                                        @if ($rapat->status == 'CANCELED')
                                            <span class="badge bg-danger">Canceled</span>
                                        @elseif ($rapat->status == 'SCHEDULED')
                                            <span class="badge bg-warning">Scheduled</span>
                                        @elseif($rapat->status == 'COMPLETED')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($rapat->status == 'STARTED')
                                            <span class="badge bg-primary">Started</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('rapat/agenda-rapat/' . $rapat->slug . '/detail') }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($rapat->user_id == Auth::user()->id || $rapat->pimpinan_id == Auth::user()->id)
                                            <a href="#" class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                        @if ($rapat->notulis_id == Auth::user()->id && $rapat->status != 'CANCELED')
                                            <a href="#" class="btn btn-success">Isi Notulen</a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($rapat->status == 'COMPLETED' || $rapat->status == 'STARTED')
                                            <a href="#" class="btn btn-primary"> Isi Penugasan</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
