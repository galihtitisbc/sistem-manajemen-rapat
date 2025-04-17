@extends('adminlte::page')
@section('title', 'Tindak Lanjut Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h5 class="m-0 text-dark">Tindak Lanjut Rapat</h5>
@stop

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            @if ($agendaRapat->isEmpty())
                <x-adminlte-card>
                    <div class="col-6 mx-auto mt-5">
                        <x-adminlte-alert theme="success">
                            Anda Tidak Memiliki Tugas
                        </x-adminlte-alert>
                    </div>
                </x-adminlte-card>
            @endif
            @if ($agendaRapat->isNotEmpty())
                <x-adminlte-card>
                    <x-adminlte-datatable id="agenda-rapat-tugas" :heads="$heads" :config="$config">
                        @foreach ($config['data'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{!! $cell !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    {{-- <table class="table table-striped mt-5">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Topik Rapat</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agendaRapat as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->agenda_rapat }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $statusTindakLanjut[$item->status_tindak_lanjut] }}">
                                        {{ $item->status_tindak_lanjut }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('rapat/tindak-lanjut-rapat/' . $item->slug . '/detail') }}"
                                        class="btn btn-secondary">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}
                </x-adminlte-card>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script></script>
@endpush
