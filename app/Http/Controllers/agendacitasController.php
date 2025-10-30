<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agenda_citas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class agendacitasController extends Controller
{
    public function agendarCita(Request $request)
    {
        try {
            // Validar los datos recibidos
            $validator = Validator::make($request->all(), [
                'nombre_cliente' => 'required|string|max:100',
                'servicio' => 'required|string|max:100',
                'fecha' => 'required|date|after:now',
                'estado' => 'sometimes|in:programada,reprogramada,cancelada',
                'notas' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors(),
                    'data' => null
                ], 422);
            }

            // Crear la nueva cita
            $cita = new Agenda_citas();
            $cita->nombre_cliente = $request->nombre_cliente;
            $cita->servicio = $request->servicio;
            $cita->fecha = $request->fecha;
            $cita->estado = $request->estado ?? 'programada';
            $cita->notas = $request->notas;
            $cita->save();

            return response()->json([
                'status' => 201,
                'message' => 'Cita agendada correctamente',
                'data' => [
                    'id_citas' => $cita->id_citas,
                    'nombre_cliente' => $cita->nombre_cliente,
                    'servicio' => $cita->servicio,
                    'fecha' => $cita->fecha,
                    'estado' => $cita->estado,
                    'notas' => $cita->notas,
                    'creado_en' => $cita->creado_en
                ]
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Ocurrió un problema al agendar la cita',
                'error' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function obtenerCitas(): JsonResponse
    {
        try {
            // Obtener todas las citas ordenadas por fecha más reciente
            $citas = Agenda_citas::orderBy('fecha', 'desc')->get();

            return response()->json([
                'status' => 200,
                'message' => 'Citas obtenidas correctamente',
                'data' => $citas
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error al obtener las citas',
                'error' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener una cita específica por ID
     */
    public function obtenerCita($id): JsonResponse
    {
        try {
            $cita = Agenda_citas::find($id);

            if (!$cita) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cita no encontrada',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Cita obtenida correctamente',
                'data' => $cita
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error al obtener la cita',
                'error' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener citas con filtros
     */
    public function obtenerCitasFiltradas(Request $request): JsonResponse
    {
        try {
            $query = Agenda_citas::query();

            // Filtrar por estado si se proporciona
            if ($request->has('estado') && $request->estado !== 'todos') {
                $query->where('estado', $request->estado);
            }

            // Filtrar por fecha si se proporciona
            if ($request->has('fecha')) {
                $query->whereDate('fecha', $request->fecha);
            }

            $citas = $query->orderBy('fecha', 'desc')->get();

            return response()->json([
                'status' => 200,
                'message' => 'Citas filtradas obtenidas correctamente',
                'data' => $citas
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error al obtener las citas filtradas',
                'error' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de citas
     */
    public function obtenerEstadisticas(): JsonResponse
    {
        try {
            $totalCitas = Agenda_citas::count();
            $citasProgramadas = Agenda_citas::where('estado', 'programada')->count();
            $citasReprogramadas = Agenda_citas::where('estado', 'reprogramada')->count();
            $citasCanceladas = Agenda_citas::where('estado', 'cancelada')->count();

            return response()->json([
                'status' => 200,
                'message' => 'Estadísticas obtenidas correctamente',
                'data' => [
                    'total' => $totalCitas,
                    'programadas' => $citasProgramadas,
                    'reprogramadas' => $citasReprogramadas,
                    'canceladas' => $citasCanceladas
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Error al obtener las estadísticas',
                'error' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
