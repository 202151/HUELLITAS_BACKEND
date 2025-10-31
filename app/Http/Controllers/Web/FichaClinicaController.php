<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FichaClinica;
use App\Models\Mascota;
use App\Models\Usuario;
use Illuminate\Http\Request;

class FichaClinicaController extends Controller
{
    public function index()
    {
        $fichas = FichaClinica::with(['mascota.propietario', 'veterinario'])
                             ->orderBy('fecha_visita', 'desc')
                             ->get();
        return view('fichas-clinicas.index', compact('fichas'));
    }

    public function create()
    {
        $mascotas = Mascota::with('propietario')->where('activo', true)->get();
        $veterinarios = Usuario::whereHas('rol', function($query) {
            $query->where('nombre', 'veterinario');
        })->where('activo', true)->get();
        
        return view('fichas-clinicas.create', compact('mascotas', 'veterinarios'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'id_mascota' => 'required|exists:mascota,id',
            'id_veterinario' => 'required|exists:usuario,id',
            'id_cita' => 'nullable|exists:citas,id',
            'fecha_visita' => 'required|date',
            'motivo' => 'required|string',
            'sintomas' => 'nullable|string',
            'examen_fisico' => 'nullable|string',
            'peso' => 'nullable|numeric|min:0',
            'temperatura' => 'nullable|numeric',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string',
            'medicamentos' => 'nullable|string',
            'recomendaciones' => 'nullable|string',
            'fecha_proxima_visita' => 'nullable|date|after:fecha_visita',
            'notas' => 'nullable|string',
        ]);

        FichaClinica::create($validado);

        return redirect()->route('fichas-clinicas.index')
                        ->with('mensaje', 'Ficha clínica creada exitosamente');
    }

    public function show($id)
    {
        $ficha = FichaClinica::with(['mascota.propietario', 'veterinario', 'cita'])
                            ->findOrFail($id);
        return view('fichas-clinicas.show', compact('ficha'));
    }

    public function edit($id)
    {
        $ficha = FichaClinica::findOrFail($id);
        $mascotas = Mascota::with('propietario')->where('activo', true)->get();
        $veterinarios = Usuario::whereHas('rol', function($query) {
            $query->where('nombre', 'veterinario');
        })->where('activo', true)->get();
        
        return view('fichas-clinicas.edit', compact('ficha', 'mascotas', 'veterinarios'));
    }

    public function update(Request $request, $id)
    {
        $ficha = FichaClinica::findOrFail($id);

        $validado = $request->validate([
            'id_mascota' => 'required|exists:mascota,id',
            'id_veterinario' => 'required|exists:usuario,id',
            'id_cita' => 'nullable|exists:citas,id',
            'fecha_visita' => 'required|date',
            'motivo' => 'required|string',
            'sintomas' => 'nullable|string',
            'examen_fisico' => 'nullable|string',
            'peso' => 'nullable|numeric|min:0',
            'temperatura' => 'nullable|numeric',
            'diagnostico' => 'required|string',
            'tratamiento' => 'required|string',
            'medicamentos' => 'nullable|string',
            'recomendaciones' => 'nullable|string',
            'fecha_proxima_visita' => 'nullable|date|after:fecha_visita',
            'notas' => 'nullable|string',
        ]);

        $ficha->update($validado);

        return redirect()->route('fichas-clinicas.index')
                        ->with('mensaje', 'Ficha clínica actualizada exitosamente');
    }
}

