@extends('layouts.main')

@section('title', 'Registro de Proyecto')

@push('styles')
    @vite(['resources/css/solicitud.css', 'resources/css/solicitud-errors.css', 'resources/css/alerts.css'])
@endpush

@section('content')

{{-- Mensajes de éxito y error --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <strong>¡Error!</strong> {{ session('error') }}
        <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <strong>¡Errores de validación!</strong>
        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

<div class="form-header-actions">
    <a href="{{ route('user') }}" class="btn-cancel-small">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Cancelar
    </a>
</div>

<form id="project-form" method="POST" action="{{ route('proyectos.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-grid">
        <aside class="form-sidebar">
            <ul class="step">
                @foreach(['Información general', 'Protocolo de investigación', 'Resultados de propuesta', 'Impactos de propuesta', 'Cronograma de actividades'] as $index => $stepName)
                    <div class="step__content">
                        <div class="step__item">
                            <div class="step__number {{ $index === 0 ? 'step--active' : '' }}" data-step="{{ $index + 1 }}">{{ $index + 1 }}</div>
                            <p class="step__text">{{ $stepName }}</p>
                        </div>
                    </div>
                @endforeach
            </ul>
        </aside>

        <main class="form-main">
            @include('partials.steps.step1')
            @include('partials.steps.step2')
            @include('partials.steps.step3')
            @include('partials.steps.step4')
            @include('partials.steps.step5')
            @include('partials.steps.step6')
            @include('partials.steps.step7')
            @include('partials.steps.step8')
            @include('partials.steps.step9')
        </main>
    </div>
</form>

@include('partials.modals.estudiante')
@include('partials.modals.profesor')
@include('partials.modals.actividad')
@include('partials.modals.impacto')

@endsection

@push('scripts')
    @vite(['resources/js/solicitud.js'])
@endpush
