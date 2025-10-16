<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['pet.owner', 'service', 'veterinarian', 'receptionist'])
                                  ->orderBy('appointment_date', 'desc')
                                  ->get();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'service_id' => 'required|exists:services,id',
            'veterinarian_id' => 'required|exists:users,id',
            'receptionist_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'duration_minutes' => 'required|integer|min:1',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:programada,confirmada,en_curso,completada,cancelada,no_asistio',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $appointment = Appointment::create($validator->validated());
        return response()->json($appointment->load(['pet.owner', 'service', 'veterinarian', 'receptionist']), 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['pet.owner', 'service', 'veterinarian', 'receptionist'])
                                 ->findOrFail($id);
        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pet_id' => 'sometimes|required|exists:pets,id',
            'service_id' => 'sometimes|required|exists:services,id',
            'veterinarian_id' => 'sometimes|required|exists:users,id',
            'receptionist_id' => 'nullable|exists:users,id',
            'appointment_date' => 'sometimes|required|date',
            'duration_minutes' => 'sometimes|required|integer|min:1',
            'reason' => 'sometimes|required|string',
            'notes' => 'nullable|string',
            'status' => 'sometimes|required|in:programada,confirmada,en_curso,completada,cancelada,no_asistio',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $appointment->update($validator->validated());
        return response()->json($appointment->load(['pet.owner', 'service', 'veterinarian', 'receptionist']));
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelada']);
        return response()->json(['message' => 'Appointment cancelled successfully']);
    }
}
