@extends('layout.app')

@section('titulo', 'Nueva Ficha Clínica')

@section('contenido')
<div class="card">
    <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 2rem;">Nueva Ficha Clínica</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fichas-clinicas.store') }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="id_mascota">Mascota *</label>
                <select name="id_mascota" id="id_mascota" class="form-control" required>
                    <option value="">Seleccionar mascota</option>
                    @foreach($mascotas as $mascota)
                        <option value="{{ $mascota->id }}" {{ old('id_mascota') == $mascota->id ? 'selected' : '' }}>
                            {{ $mascota->nombre }} - {{ $mascota->propietario->nombre_completo ?? 'Sin propietario' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_veterinario">Veterinario *</label>
                <select name="id_veterinario" id="id_veterinario" class="form-control" required>
                    <option value="">Seleccionar veterinario</option>
                    @foreach($veterinarios as $veterinario)
                        <option value="{{ $veterinario->id }}" {{ old('id_veterinario') == $veterinario->id ? 'selected' : '' }}>
                            {{ $veterinario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="fecha_visita">Fecha de Visita *</label>
                <input type="date" name="fecha_visita" id="fecha_visita" class="form-control" value="{{ old('fecha_visita', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="peso">Peso (kg)</label>
                <input type="number" step="0.01" name="peso" id="peso" class="form-control" value="{{ old('peso') }}" placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="temperatura">Temperatura (°C)</label>
                <input type="number" step="0.1" name="temperatura" id="temperatura" class="form-control" value="{{ old('temperatura') }}" placeholder="0.0">
            </div>
        </div>

        <div class="form-group">
            <label for="motivo">Motivo de la Consulta *</label>
            <textarea name="motivo" id="motivo" class="form-control" required>{{ old('motivo') }}</textarea>
        </div>

        <div class="form-group">
            <label for="sintomas">Síntomas</label>
            <textarea name="sintomas" id="sintomas" class="form-control">{{ old('sintomas') }}</textarea>
        </div>

        <div class="form-group">
            <label for="examen_fisico">Examen Físico</label>
            <textarea name="examen_fisico" id="examen_fisico" class="form-control">{{ old('examen_fisico') }}</textarea>
        </div>

        <div class="form-group">
            <label for="diagnostico">Diagnóstico *</label>
            <textarea name="diagnostico" id="diagnostico" class="form-control" required>{{ old('diagnostico') }}</textarea>
        </div>

        <div class="form-group">
            <label for="tratamiento">Tratamiento *</label>
            <textarea name="tratamiento" id="tratamiento" class="form-control" required>{{ old('tratamiento') }}</textarea>
        </div>

        <div class="form-group">
            <label for="medicamentos">Medicamentos</label>
            <textarea name="medicamentos" id="medicamentos" class="form-control">{{ old('medicamentos') }}</textarea>
        </div>

        <div class="form-group">
            <label for="recomendaciones">Recomendaciones</label>
            <textarea name="recomendaciones" id="recomendaciones" class="form-control">{{ old('recomendaciones') }}</textarea>
        </div>

        <div class="form-group">
            <label for="fecha_proxima_visita">Fecha Próxima Visita</label>
            <input type="date" name="fecha_proxima_visita" id="fecha_proxima_visita" class="form-control" value="{{ old('fecha_proxima_visita') }}">
        </div>

        <div class="form-group">
            <label for="notas">Notas Adicionales</label>
            <textarea name="notas" id="notas" class="form-control">{{ old('notas') }}</textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-success">Guardar Ficha Clínica</button>
            <a href="{{ route('fichas-clinicas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

