<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agenda_citas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
