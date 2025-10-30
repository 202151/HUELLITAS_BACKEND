<?php

namespace App\Http\Controllers;

use App\Models\Mascotas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MascotasController extends Controller
{
    /**
     * Listar todas las mascotas
     */
    public function index()
    {
        try {
            $mascotas = Mascotas::with('propietario')->get();
            
            return response()->json([
                'success' => true,
                'data' => $mascotas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las mascotas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva mascota
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'raza' => 'nullable|string|max:50',
            'edad' => 'nullable|integer|min:0',
            'id_propietario' => 'required|integer|exists:propietario,id_propietario'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mascota = Mascotas::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Mascota creada exitosamente',
                'data' => $mascota
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la mascota',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una mascota específica
     */
    public function show($id)
    {
        try {
            $mascota = Mascotas::with('propietario')->find($id);
            
            if (!$mascota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mascota no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $mascota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la mascota',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una mascota
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:100',
            'raza' => 'nullable|string|max:50',
            'edad' => 'nullable|integer|min:0',
            'id_propietario' => 'sometimes|required|integer|exists:propietario,id_propietario'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mascota = Mascotas::find($id);
            
            if (!$mascota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mascota no encontrada'
                ], 404);
            }

            $mascota->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Mascota actualizada exitosamente',
                'data' => $mascota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la mascota',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una mascota
     */
    public function destroy($id)
    {
        try {
            $mascota = Mascotas::find($id);
            
            if (!$mascota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mascota no encontrada'
                ], 404);
            }

            $mascota->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mascota eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la mascota',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}