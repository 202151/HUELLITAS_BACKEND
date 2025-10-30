<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VaccinationController extends Controller
{
    public function index()
    {
        $vaccinations = Vaccination::with(['pet.owner', 'veterinarian'])
                                  ->orderBy('application_date', 'desc')
                                  ->get();
        return response()->json($vaccinations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:users,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'type' => 'required|in:vacuna,desparasitacion',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:100',
            'application_date' => 'required|date',
            'expiration_date' => 'nullable|date|after:application_date',
            'next_dose_date' => 'nullable|date|after:application_date',
            'weight_at_application' => 'nullable|numeric|min:0',
            'adverse_reactions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vaccination = Vaccination::create($validator->validated());
        return response()->json($vaccination->load(['pet.owner', 'veterinarian']), 201);
    }

    public function show($id)
    {
        $vaccination = Vaccination::with(['pet.owner', 'veterinarian'])
                                 ->findOrFail($id);
        return response()->json($vaccination);
    }

    public function update(Request $request, $id)
    {
        $vaccination = Vaccination::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'pet_id' => 'sometimes|required|exists:pets,id',
            'veterinarian_id' => 'sometimes|required|exists:users,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'type' => 'sometimes|required|in:vacuna,desparasitacion',
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:100',
            'application_date' => 'sometimes|required|date',
            'expiration_date' => 'nullable|date|after:application_date',
            'next_dose_date' => 'nullable|date|after:application_date',
            'weight_at_application' => 'nullable|numeric|min:0',
            'adverse_reactions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vaccination->update($validator->validated());
        return response()->json($vaccination->load(['pet.owner', 'veterinarian']));
    }

    public function destroy($id)
    {
        $vaccination = Vaccination::findOrFail($id);
        $vaccination->delete();
        return response()->json(['message' => 'Vaccination record deleted successfully']);
    }
}
