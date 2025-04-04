@extends('adminlte::page')
@section('title', 'Rapat')
{{-- @section('plugins.Select2', true) --}}
@section('content_header')
    <h1 class="m-0 text-dark"></h1>
@stop

@push('css')
    @livewireStyles
@endpush

@section('content')
    <x-adminlte-card>
        @livewire('rapat.rapat-edit-form', ['agendaRapatLoad' => $rapat, 'allUsers' => $users, 'kepanitiaans' => $kepanitiaans])
    </x-adminlte-card>
@endsection

@push('js')
    @livewireScripts
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener("swal", (event) => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: event.detail.icon
                });
            });
        });
    </script>
@endpush
