@extends('layout.app')

@section('titulo', 'Fichas Clínicas')

@section('contenido')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.875rem; font-weight: 700;">Fichas Clínicas</h2>
        <a href="{{ route('fichas-clinicas.create') }}" class="btn btn-primary">
            + Nueva Ficha Clínica
        </a>
    </div>

    @if(session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha Visita</th>
                    <th>Mascota</th>
                    <th>Propietario</th>
                    <th>Veterinario</th>
                    <th>Motivo</th>
                    <th>Diagnóstico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fichas as $ficha)
                <tr>
                    <td>{{ $ficha->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($ficha->fecha_visita)->format('d/m/Y') }}</td>
                    <td>{{ $ficha->mascota->nombre ?? 'N/A' }}</td>
                    <td>{{ $ficha->mascota->propietario->nombre_completo ?? 'N/A' }}</td>
                    <td>{{ $ficha->veterinario->nombre ?? 'N/A' }}</td>
                    <td>{{ Str::limit($ficha->motivo, 50) }}</td>
                    <td>{{ Str::limit($ficha->diagnostico, 50) }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('fichas-clinicas.show', $ficha->id) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Ver</a>
                            <a href="{{ route('fichas-clinicas.edit', $ficha->id) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Editar</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">
                        No hay fichas clínicas registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

