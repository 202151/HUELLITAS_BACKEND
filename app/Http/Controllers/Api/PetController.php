<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['owner', 'appointments', 'medicalRecords', 'vaccinations'])
                   ->where('is_active', true)
                   ->get();
        return response()->json($pets);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:100',
            'breed' => 'nullable|string|max:100',
            'gender' => 'required|in:M,F',
            'birth_date' => 'nullable|date',
            'weight' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:100',
            'distinctive_marks' => 'nullable|string',
            'microchip_number' => 'nullable|string|unique:pets,microchip_number',
            'is_sterilized' => 'boolean',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'photo_path' => 'nullable|string',
            'owner_id' => 'required|exists:owners,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pet = Pet::create($validator->validated());
        return response()->json($pet->load('owner'), 201);
    }

    public function show($id)
    {
        $pet = Pet::with(['owner', 'appointments.service', 'medicalRecords.veterinarian', 'vaccinations.veterinarian'])
                  ->findOrFail($id);
        return response()->json($pet);
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'species' => 'sometimes|required|string|max:100',
            'breed' => 'nullable|string|max:100',
            'gender' => 'sometimes|required|in:M,F',
            'birth_date' => 'nullable|date',
            'weight' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:100',
            'distinctive_marks' => 'nullable|string',
            'microchip_number' => 'nullable|string|unique:pets,microchip_number,' . $id,
            'is_sterilized' => 'boolean',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'photo_path' => 'nullable|string',
            'owner_id' => 'sometimes|required|exists:owners,id',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pet->update($validator->validated());
        return response()->json($pet->load('owner'));
    }

    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);
        $pet->update(['is_active' => false]);
        return response()->json(['message' => 'Pet deactivated successfully']);
    }
}
