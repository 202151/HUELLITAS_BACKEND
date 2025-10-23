<?php

namespace App\Http\Controllers;

use App\Models\Desparasitacion;
use App\Models\Mascota;
use Illuminate\Http\Request;

class DesparasitacionController extends Controller
{
    public function  Lista()
    {
        $desparasitaciones = Desparasitacion::with('mascota')->get();
        return response()->json($desparasitaciones);
    }

    
    public function Muestra($id)
    {
        $desparasitacion = Desparasitacion::with('mascota')->find($id);
        if (!$desparasitacion) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        return response()->json($desparasitacion);
    }

    
    public function Crear(Request $request)
    {
        $validated = $request->validate([
            'id_mascota' => 'required|exists:mascota,id_mascota',
            'tipo_producto' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date',
            'proxima_aplicacion' => 'nullable|date',
            'via_administracion' => 'nullable|in:oral,tópica,inyectable',
            'observaciones' => 'nullable|string',
        ]);

        $desparasitacion = Desparasitacion::create($validated);

        return response()->json([
            'message' => 'Registro creado correctamente',
            'data' => $desparasitacion
        ], 201);
    }

    public function Actualizar(Request $request, $id)
    {
        $desparasitacion = Desparasitacion::find($id);
        if (!$desparasitacion) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $validated = $request->validate([
            'id_mascota' => 'required|exists:mascota,id_mascota',
            'tipo_producto' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date',
            'proxima_aplicacion' => 'nullable|date',
            'via_administracion' => 'nullable|in:oral,tópica,inyectable',
            'observaciones' => 'nullable|string',
        ]);

        $desparasitacion->update($validated);

        return response()->json([
            'message' => 'Registro actualizado correctamente',
            'data' => $desparasitacion
        ]);
    }

    
    public function Eliminar($id)
    {
        $desparasitacion = Desparasitacion::find($id);
        if (!$desparasitacion) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $desparasitacion->delete();

        return response()->json(['message' => 'Registro eliminado correctamente']);
    }
}
