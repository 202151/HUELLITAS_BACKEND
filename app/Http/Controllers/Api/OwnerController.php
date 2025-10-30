<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::with('pets')->where('is_active', true)->get();
        return response()->json($owners);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_type' => 'required|in:CC,CE,TI,PP',
            'document_number' => 'required|string|unique:owners,document_number',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:owners,email',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $owner = Owner::create($validator->validated());
        return response()->json($owner, 201);
    }

    public function show($id)
    {
        $owner = Owner::with('pets')->findOrFail($id);
        return response()->json($owner);
    }

    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'document_type' => 'sometimes|required|in:CC,CE,TI,PP',
            'document_number' => 'sometimes|required|string|unique:owners,document_number,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|unique:owners,email,' . $id,
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'notes' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $owner->update($validator->validated());
        return response()->json($owner);
    }

    public function destroy($id)
    {
        $owner = Owner::findOrFail($id);
        $owner->update(['is_active' => false]);
        return response()->json(['message' => 'Owner deactivated successfully']);
    }
}
