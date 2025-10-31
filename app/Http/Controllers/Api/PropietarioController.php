<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropietarioController extends Controller
{
    public function index()
    {
        $propietarios = Propietario::with('mascotas')->where('activo', true)->get();
        return response()->json($propietarios);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:150',
            'tipo_documento' => 'required|in:DNI,CC,CE,TI,PP',
            'numero_documento' => 'required|string|unique:propietario,numero_documento',
            'numero_cell' => 'required|string|max:30',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:250',
            'ciudad' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F,Otro',
            'notas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $propietario = Propietario::create($validator->validated());
        return response()->json($propietario, 201);
    }

    public function show($id)
    {
        $propietario = Propietario::with('mascotas')->findOrFail($id);
        return response()->json($propietario);
    }

    public function update(Request $request, $id)
    {
        $propietario = Propietario::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'sometimes|required|string|max:150',
            'tipo_documento' => 'sometimes|required|in:DNI,CC,CE,TI,PP',
            'numero_documento' => 'sometimes|required|string|unique:propietario,numero_documento,' . $id,
            'numero_cell' => 'sometimes|required|string|max:30',
            'correo' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:250',
            'ciudad' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F,Otro',
            'notas' => 'nullable|string',
            'activo' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errores' => $validator->errors()], 422);
        }

        $propietario->update($validator->validated());
        return response()->json($propietario);
    }

    public function destroy($id)
    {
        $propietario = Propietario::findOrFail($id);
        $propietario->update(['activo' => false]);
        return response()->json(['mensaje' => 'Propietario desactivado exitosamente']);
    }
}

