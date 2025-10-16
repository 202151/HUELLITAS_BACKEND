<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['pet.owner', 'veterinarian'])
                                     ->orderBy('visit_date', 'desc')
                                     ->get();
        return response()->json($medicalRecords);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:users,id',
            'visit_date' => 'required|date',
            'reason_for_visit' => 'required|string',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric',
            'heart_rate' => 'nullable|integer|min:0',
            'respiratory_rate' => 'nullable|integer|min:0',
            'blood_pressure' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:visit_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $medicalRecord = MedicalRecord::create($validator->validated());
        return response()->json($medicalRecord->load(['pet.owner', 'veterinarian']), 201);
    }

    public function show($id)
    {
        $medicalRecord = MedicalRecord::with(['pet.owner', 'veterinarian'])
                                    ->findOrFail($id);
        return response()->json($medicalRecord);
    }

    public function update(Request $request, $id)
    {
        $medicalRecord = MedicalRecord::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pet_id' => 'sometimes|required|exists:pets,id',
            'veterinarian_id' => 'sometimes|required|exists:users,id',
            'visit_date' => 'sometimes|required|date',
            'reason_for_visit' => 'sometimes|required|string',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'medications' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric',
            'heart_rate' => 'nullable|integer|min:0',
            'respiratory_rate' => 'nullable|integer|min:0',
            'blood_pressure' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:visit_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $medicalRecord->update($validator->validated());
        return response()->json($medicalRecord->load(['pet.owner', 'veterinarian']));
    }

    public function destroy($id)
    {
        $medicalRecord = MedicalRecord::findOrFail($id);
        $medicalRecord->delete();
        return response()->json(['message' => 'Medical record deleted successfully']);
    }
}
