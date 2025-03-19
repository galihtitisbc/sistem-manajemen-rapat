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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <livewire:rapat.rapat-create-form :allUsers=$users :kepanitiaans=$kepanitiaans />
                </div>
            </div>
        </div>
    </div>
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
