<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FichaClinica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FichaClinicaController extends Controller
{
    public function index()
    {
        $fichasClinicas = FichaClinica::with(['mascota.propietario', 'veterinario'])
                                     ->orderBy('fecha_visita', 'desc')
                                     ->get();
        return response()->json($fichasClinicas);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'adjuntos' => 'nullable|array',
            'notas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $fichaClinica = FichaClinica::create($validator->validated());
        return response()->json($fichaClinica->load(['mascota.propietario', 'veterinario']), 201);
    }

    public function show($id)
    {
        $fichaClinica = FichaClinica::with(['mascota.propietario', 'veterinario', 'cita'])
                                    ->findOrFail($id);
        return response()->json($fichaClinica);
    }

    public function update(Request $request, $id)
    {
        $fichaClinica = FichaClinica::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_mascota' => 'sometimes|required|exists:mascota,id',
            'id_veterinario' => 'sometimes|required|exists:usuario,id',
            'id_cita' => 'nullable|exists:citas,id',
            'fecha_visita' => 'sometimes|required|date',
            'motivo' => 'sometimes|required|string',
            'sintomas' => 'nullable|string',
            'examen_fisico' => 'nullable|string',
            'peso' => 'nullable|numeric|min:0',
            'temperatura' => 'nullable|numeric',
            'diagnostico' => 'sometimes|required|string',
            'tratamiento' => 'sometimes|required|string',
            'medicamentos' => 'nullable|string',
            'recomendaciones' => 'nullable|string',
            'fecha_proxima_visita' => 'nullable|date|after:fecha_visita',
            'adjuntos' => 'nullable|array',
            'notas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $fichaClinica->update($validator->validated());
        return response()->json($fichaClinica->load(['mascota.propietario', 'veterinario']));
    }

    public function fichasPorMascota($idMascota)
    {
        $fichas = FichaClinica::where('id_mascota', $idMascota)
                             ->with(['veterinario', 'cita'])
                             ->orderBy('fecha_visita', 'desc')
                             ->get();
        return response()->json($fichas);
    }
}

