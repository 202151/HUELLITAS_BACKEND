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
                                  ->orderBy('vaccination_date', 'desc')
                                  ->get();
        return response()->json($vaccinations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:users,id',
            'vaccine_name' => 'required|string|max:255',
            'vaccine_type' => 'required|string|max:100',
            'vaccination_date' => 'required|date',
            'next_vaccination_date' => 'nullable|date|after:vaccination_date',
            'batch_number' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
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
            'vaccine_name' => 'sometimes|required|string|max:255',
            'vaccine_type' => 'sometimes|required|string|max:100',
            'vaccination_date' => 'sometimes|required|date',
            'next_vaccination_date' => 'nullable|date|after:vaccination_date',
            'batch_number' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
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
