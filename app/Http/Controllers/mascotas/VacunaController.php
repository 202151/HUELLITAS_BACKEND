<?php

namespace App\Http\Controllers;

use App\Models\Vacuna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class VacunaController extends Controller
{
  
    /**
     * Listar todas las vacunas con filtros opcionales
     * GET /api/vacunas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Vacuna::with(['mascota' => function($q) {
                $q->select('id_mascota', 'nombre', 'especie');
            }]);

            // Filtrar por mascota
            if ($request->filled('id_mascota')) {
                $query->where('id_mascota', $request->id_mascota);
            }

            // Filtrar por nombre de vacuna
            if ($request->filled('nombre_vacuna')) {
                $query->where('nombre_vacuna', 'like', '%' . $request->nombre_vacuna . '%');
            }

            // Filtrar por rango de fechas
            if ($request->filled('fecha_desde')) {
                $query->where('fecha_aplicacion', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->where('fecha_aplicacion', '<=', $request->fecha_hasta);
            }

            // Ordenamiento
            $orden = $request->get('orden', 'desc');
            $vacunas = $query->orderBy('fecha_aplicacion', $orden)->get();

            return response()->json([
                'success' => true,
                'message' => 'Lista de vacunas obtenida correctamente',
                'data' => $vacunas,
                'total' => $vacunas->count()
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las vacunas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una nueva vacuna
     * POST /api/vacunas
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_mascota' => 'required|integer|exists:mascota,id_mascota',
            'nombre_vacuna' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'proxima_dosis' => 'nullable|date|after:fecha_aplicacion',
            'observaciones' => 'nullable|string|max:1000',
        ], [
            'id_mascota.required' => 'El ID de la mascota es obligatorio',
            'id_mascota.exists' => 'La mascota seleccionada no existe en el sistema',
            'nombre_vacuna.required' => 'El nombre de la vacuna es obligatorio',
            'nombre_vacuna.max' => 'El nombre de la vacuna no puede exceder 100 caracteres',
            'fecha_aplicacion.required' => 'La fecha de aplicación es obligatoria',
            'fecha_aplicacion.date' => 'La fecha de aplicación debe ser una fecha válida',
            'fecha_aplicacion.before_or_equal' => 'La fecha de aplicación no puede ser una fecha futura',
            'proxima_dosis.date' => 'La próxima dosis debe ser una fecha válida',
            'proxima_dosis.after' => 'La próxima dosis debe ser posterior a la fecha de aplicación',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $vacuna = Vacuna::create($validator->validated());
            $vacuna->load(['mascota' => function($q) {
                $q->select('id_mascota', 'nombre', 'especie');
            }]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vacuna registrada exitosamente',
                'data' => $vacuna
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la vacuna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar una vacuna específica
     * GET /api/vacunas/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $vacuna = Vacuna::with(['mascota' => function($q) {
                $q->select('id_mascota', 'nombre', 'especie', 'raza', 'edad');
            }])->find($id);

            if (!$vacuna) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vacuna no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vacuna obtenida correctamente',
                'data' => $vacuna
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la vacuna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar una vacuna existente
     * PUT/PATCH /api/vacunas/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $vacuna = Vacuna::find($id);

        if (!$vacuna) {
            return response()->json([
                'success' => false,
                'message' => 'Vacuna no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_mascota' => 'required|integer|exists:mascota,id_mascota',
            'nombre_vacuna' => 'required|string|max:100',
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'proxima_dosis' => 'nullable|date|after:fecha_aplicacion',
            'observaciones' => 'nullable|string|max:1000',
        ], [
            'id_mascota.required' => 'El ID de la mascota es obligatorio',
            'id_mascota.exists' => 'La mascota seleccionada no existe en el sistema',
            'nombre_vacuna.required' => 'El nombre de la vacuna es obligatorio',
            'nombre_vacuna.max' => 'El nombre de la vacuna no puede exceder 100 caracteres',
            'fecha_aplicacion.required' => 'La fecha de aplicación es obligatoria',
            'fecha_aplicacion.date' => 'La fecha de aplicación debe ser una fecha válida',
            'fecha_aplicacion.before_or_equal' => 'La fecha de aplicación no puede ser una fecha futura',
            'proxima_dosis.date' => 'La próxima dosis debe ser una fecha válida',
            'proxima_dosis.after' => 'La próxima dosis debe ser posterior a la fecha de aplicación',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $vacuna->update($validator->validated());
            $vacuna->load(['mascota' => function($q) {
                $q->select('id_mascota', 'nombre', 'especie');
            }]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vacuna actualizada exitosamente',
                'data' => $vacuna
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la vacuna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una vacuna
     * DELETE /api/vacunas/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $vacuna = Vacuna::find($id);

            if (!$vacuna) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vacuna no encontrada'
                ], 404);
            }

            $nombreVacuna = $vacuna->nombre_vacuna;
            $vacuna->delete();

            return response()->json([
                'success' => true,
                'message' => "Vacuna '{$nombreVacuna}' eliminada exitosamente"
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la vacuna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener vacunas próximas a vencer
     * GET /api/vacunas/proximas?dias=30
     */
    public function proximasAVencer(Request $request): JsonResponse
    {
        try {
            $dias = $request->get('dias', 30);
            
            $vacunas = Vacuna::with(['mascota' => function($q) {
                    $q->select('id_mascota', 'nombre', 'especie');
                }])
                ->whereNotNull('proxima_dosis')
                ->whereBetween('proxima_dosis', [now()->startOfDay(), now()->addDays($dias)->endOfDay()])
                ->orderBy('proxima_dosis', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => "Vacunas con próxima dosis en los próximos {$dias} días",
                'data' => $vacunas,
                'total' => $vacunas->count(),
                'parametros' => [
                    'dias' => $dias,
                    'fecha_inicio' => now()->startOfDay()->format('Y-m-d'),
                    'fecha_fin' => now()->addDays($dias)->endOfDay()->format('Y-m-d')
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener vacunas próximas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener historial completo de vacunas de una mascota
     * GET /api/vacunas/mascota/{idMascota}
     */
    public function historialMascota(int $idMascota): JsonResponse
    {
        try {
            // Verificar si la mascota existe
            $mascotaExiste = DB::table('mascota')
                ->where('id_mascota', $idMascota)
                ->exists();

            if (!$mascotaExiste) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mascota no encontrada'
                ], 404);
            }

            $vacunas = Vacuna::where('id_mascota', $idMascota)
                ->orderBy('fecha_aplicacion', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Historial de vacunas obtenido correctamente',
                'data' => $vacunas,
                'total' => $vacunas->count(),
                'id_mascota' => $idMascota
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el historial de vacunas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener vacunas vencidas (próxima dosis ya pasó)
     * GET /api/vacunas/vencidas
     */
    public function vencidas(): JsonResponse
    {
        try {
            $vacunas = Vacuna::with(['mascota' => function($q) {
                    $q->select('id_mascota', 'nombre', 'especie');
                }])
                ->whereNotNull('proxima_dosis')
                ->where('proxima_dosis', '<', now()->startOfDay())
                ->orderBy('proxima_dosis', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Vacunas vencidas obtenidas correctamente',
                'data' => $vacunas,
                'total' => $vacunas->count(),
                'fecha_referencia' => now()->format('Y-m-d')
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener vacunas vencidas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de vacunación
     * GET /api/vacunas/estadisticas
     */
    public function estadisticas(): JsonResponse
    {
        try {
            $totalVacunas = Vacuna::count();
            $proximasDosis = Vacuna::whereNotNull('proxima_dosis')
                ->whereBetween('proxima_dosis', [now(), now()->addDays(30)])
                ->count();
            $dosisVencidas = Vacuna::whereNotNull('proxima_dosis')
                ->where('proxima_dosis', '<', now())
                ->count();
            
            // Vacunas más aplicadas
            $vacunasMasAplicadas = Vacuna::select('nombre_vacuna', DB::raw('COUNT(*) as total'))
                ->groupBy('nombre_vacuna')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Estadísticas obtenidas correctamente',
                'data' => [
                    'total_vacunas' => $totalVacunas,
                    'proximas_dosis_30_dias' => $proximasDosis,
                    'dosis_vencidas' => $dosisVencidas,
                    'vacunas_mas_aplicadas' => $vacunasMasAplicadas
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar próxima dosis como aplicada (crear nuevo registro)
     * POST /api/vacunas/{id}/aplicar-dosis
     */
    public function aplicarProximaDosis(Request $request, int $id): JsonResponse
    {
        $vacunaAnterior = Vacuna::find($id);

        if (!$vacunaAnterior) {
            return response()->json([
                'success' => false,
                'message' => 'Vacuna no encontrada'
            ], 404);
        }

        if (!$vacunaAnterior->proxima_dosis) {
            return response()->json([
                'success' => false,
                'message' => 'Esta vacuna no tiene próxima dosis programada'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'fecha_aplicacion' => 'required|date|before_or_equal:today',
            'proxima_dosis' => 'nullable|date|after:fecha_aplicacion',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Crear nuevo registro de vacuna
            $nuevaVacuna = Vacuna::create([
                'id_mascota' => $vacunaAnterior->id_mascota,
                'nombre_vacuna' => $vacunaAnterior->nombre_vacuna,
                'fecha_aplicacion' => $request->fecha_aplicacion,
                'proxima_dosis' => $request->proxima_dosis,
                'observaciones' => $request->observaciones ?? 'Aplicación de dosis programada',
            ]);

            $nuevaVacuna->load(['mascota' => function($q) {
                $q->select('id_mascota', 'nombre', 'especie');
            }]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dosis aplicada y registrada exitosamente',
                'data' => $nuevaVacuna
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al aplicar la dosis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
