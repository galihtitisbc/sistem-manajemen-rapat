@extends('adminlte::page')
@section('title', 'Kepanitiaan')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h3 class="m-0 text-dark">Kepanitiaan</h3>
@stop

@push('css')
@endpush

@section('content')
    @php
        $heads = [
            'ID',
            'Nama Kepanitiaan',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Status',
            ['label' => 'Actions', 'no-export' => true, 'width' => 15],
        ];
    @endphp
    <x-adminlte-card>
        @hasanyrole(['pimpinan', 'kepegawaian'])
            <div class="btn-tambah d-flex justify-content-end my-2">
                <a href="{{ url('rapat/panitia/create') }}" class="btn btn-primary">Tambah Kepanitiaan</a>
            </div>
        @endhasanyrole
        <x-adminlte-datatable id="table1" :heads="$heads">
            @foreach ($kepanitiaans as $kepanitiaan)
                <tr>
                    <td>{{ $kepanitiaan->id }}</td>
                    <td>{{ $kepanitiaan->nama_kepanitiaan }}</td>
                    <td>{{ $kepanitiaan->tanggal_mulai }}</td>
                    <td>{{ $kepanitiaan->tanggal_berakhir }}</td>
                    <td>
                        <span class="badge bg-{{ $kepanitiaan->status == 'AKTIF' ? 'success' : 'danger' }}">
                            {{ $kepanitiaan->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ url('/rapat/panitia/' . $kepanitiaan->id . '/detail') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Detail Kepanitiaan"></i>
                        </a>
                        @hasrole('kepegawaian')
                            <a href="{{ url('/rapat/panitia/' . $kepanitiaan->id . '/edit') }}" class="btn btn-warning btn-sm"
                                title="Edit">
                                <i class="fa fa-fw fa-pen"></i>
                                <nobr>
                            </a>
                            <form action="{{ url('/rapat/panitia/' . $kepanitiaan->id . '/change-status') }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-danger btn-sm" title="Ubah Status">
                                    <i class="fa fa-fw fa-exchange-alt"></i>
                                </button>
                            </form>
                        @endhasrole
                        </nobr>
                    </td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
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
