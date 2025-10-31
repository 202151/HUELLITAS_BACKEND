@extends('layout.app')

@section('titulo', 'Detalle de Ficha Clínica')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Ficha Clínica #{{ $ficha->id }}</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('fichas-clinicas.edit', $ficha->id) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('fichas-clinicas.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Información General</h3>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                <p style="margin-bottom: 0.5rem;"><strong>Fecha de Visita:</strong> {{ \Carbon\Carbon::parse($ficha->fecha_visita)->format('d/m/Y') }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Mascota:</strong> {{ $ficha->mascota->nombre ?? 'N/A' }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Propietario:</strong> {{ $ficha->mascota->propietario->nombre_completo ?? 'N/A' }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Veterinario:</strong> {{ $ficha->veterinario->nombre ?? 'N/A' }}</p>
            </div>
        </div>

        <div>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Signos Vitales</h3>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                <p style="margin-bottom: 0.5rem;"><strong>Peso:</strong> {{ $ficha->peso ? $ficha->peso . ' kg' : 'No registrado' }}</p>
                <p style="margin-bottom: 0.5rem;"><strong>Temperatura:</strong> {{ $ficha->temperatura ? $ficha->temperatura . ' °C' : 'No registrada' }}</p>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Motivo de la Consulta</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <p>{{ $ficha->motivo }}</p>
        </div>
    </div>

    @if($ficha->sintomas)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Síntomas</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <p>{{ $ficha->sintomas }}</p>
        </div>
    </div>
    @endif

    @if($ficha->examen_fisico)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Examen Físico</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <p>{{ $ficha->examen_fisico }}</p>
        </div>
    </div>
    @endif

    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Diagnóstico</h3>
        <div style="background: #dbeafe; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border-left: 4px solid #3b82f6;">
            <p>{{ $ficha->diagnostico }}</p>
        </div>
    </div>

    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Tratamiento</h3>
        <div style="background: #d1fae5; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border-left: 4px solid #10b981;">
            <p>{{ $ficha->tratamiento }}</p>
        </div>
    </div>

    @if($ficha->medicamentos)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Medicamentos</h3>
        <div style="background: #fef3c7; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border-left: 4px solid #f59e0b;">
            <p>{{ $ficha->medicamentos }}</p>
        </div>
    </div>
    @endif

    @if($ficha->recomendaciones)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Recomendaciones</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <p>{{ $ficha->recomendaciones }}</p>
        </div>
    </div>
    @endif

    @if($ficha->fecha_proxima_visita)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Próxima Visita</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <p><strong>Fecha programada:</strong> {{ \Carbon\Carbon::parse($ficha->fecha_proxima_visita)->format('d/m/Y') }}</p>
        </div>
    </div>
    @endif

    @if($ficha->notas)
    <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: #374151;">Notas Adicionales</h3>
        <div style="background: #f9fafb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <p>{{ $ficha->notas }}</p>
        </div>
    </div>
    @endif

    <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 0.875rem; color: #6b7280;">
            <strong>Creado:</strong> {{ \Carbon\Carbon::parse($ficha->created_at)->format('d/m/Y H:i') }} |
            <strong>Última actualización:</strong> {{ \Carbon\Carbon::parse($ficha->updated_at)->format('d/m/Y H:i') }}
        </p>
    </div>
</div>
@endsection

