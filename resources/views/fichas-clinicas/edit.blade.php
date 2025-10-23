@extends('layout.app')

@section('titulo', 'Editar Ficha Clínica')

@section('contenido')
<div class="card">
    <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 2rem;">Editar Ficha Clínica #{{ $ficha->id }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('fichas-clinicas.update', $ficha->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="id_mascota">Mascota *</label>
                <select name="id_mascota" id="id_mascota" class="form-control" required>
                    @foreach($mascotas as $mascota)
                        <option value="{{ $mascota->id }}" {{ $ficha->id_mascota == $mascota->id ? 'selected' : '' }}>
                            {{ $mascota->nombre }} - {{ $mascota->propietario->nombre_completo ?? 'Sin propietario' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="id_veterinario">Veterinario *</label>
                <select name="id_veterinario" id="id_veterinario" class="form-control" required>
                    @foreach($veterinarios as $veterinario)
                        <option value="{{ $veterinario->id }}" {{ $ficha->id_veterinario == $veterinario->id ? 'selected' : '' }}>
                            {{ $veterinario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="fecha_visita">Fecha de Visita *</label>
                <input type="date" name="fecha_visita" id="fecha_visita" class="form-control" value="{{ $ficha->fecha_visita->format('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label for="peso">Peso (kg)</label>
                <input type="number" step="0.01" name="peso" id="peso" class="form-control" value="{{ $ficha->peso }}" placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="temperatura">Temperatura (°C)</label>
                <input type="number" step="0.1" name="temperatura" id="temperatura" class="form-control" value="{{ $ficha->temperatura }}" placeholder="0.0">
            </div>
        </div>

        <div class="form-group">
            <label for="motivo">Motivo de la Consulta *</label>
            <textarea name="motivo" id="motivo" class="form-control" required>{{ $ficha->motivo }}</textarea>
        </div>

        <div class="form-group">
            <label for="sintomas">Síntomas</label>
            <textarea name="sintomas" id="sintomas" class="form-control">{{ $ficha->sintomas }}</textarea>
        </div>

        <div class="form-group">
            <label for="examen_fisico">Examen Físico</label>
            <textarea name="examen_fisico" id="examen_fisico" class="form-control">{{ $ficha->examen_fisico }}</textarea>
        </div>

        <div class="form-group">
            <label for="diagnostico">Diagnóstico *</label>
            <textarea name="diagnostico" id="diagnostico" class="form-control" required>{{ $ficha->diagnostico }}</textarea>
        </div>

        <div class="form-group">
            <label for="tratamiento">Tratamiento *</label>
            <textarea name="tratamiento" id="tratamiento" class="form-control" required>{{ $ficha->tratamiento }}</textarea>
        </div>

        <div class="form-group">
            <label for="medicamentos">Medicamentos</label>
            <textarea name="medicamentos" id="medicamentos" class="form-control">{{ $ficha->medicamentos }}</textarea>
        </div>

        <div class="form-group">
            <label for="recomendaciones">Recomendaciones</label>
            <textarea name="recomendaciones" id="recomendaciones" class="form-control">{{ $ficha->recomendaciones }}</textarea>
        </div>

        <div class="form-group">
            <label for="fecha_proxima_visita">Fecha Próxima Visita</label>
            <input type="date" name="fecha_proxima_visita" id="fecha_proxima_visita" class="form-control" value="{{ $ficha->fecha_proxima_visita ? $ficha->fecha_proxima_visita->format('Y-m-d') : '' }}">
        </div>

        <div class="form-group">
            <label for="notas">Notas Adicionales</label>
            <textarea name="notas" id="notas" class="form-control">{{ $ficha->notas }}</textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-success">Actualizar Ficha Clínica</button>
            <a href="{{ route('fichas-clinicas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

