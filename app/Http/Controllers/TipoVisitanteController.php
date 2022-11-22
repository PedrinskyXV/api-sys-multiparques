<?php

namespace App\Http\Controllers;

use App\Models\TipoVisitante;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TipoVisitanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = TipoVisitante::all();

        return response()->json([
            'success' => true,
            'message' => 'Listado de tipos de visitantes',
            'data' => $tipos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'tipo' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $tipo = TipoVisitante::create($input);

        return response()->json([
            "success" => true,
            "message" => "Tipo de visitante creado correctamente.",
            "data" => $tipo,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoVisitante  $tipoVisitante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo = TipoVisitante::findOrFail($id);
        
        if (is_null($tipo)) {
            return response()->json('Tipo de visitante no encontrado.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            "success" => true,
            "message" => "Tipo de visitante encontrado.",
            "data" => $tipo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoVisitante  $tipoVisitante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'tipo' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $tipoVisitante = TipoVisitante::findOrFail($id);
        $tipoVisitante->update($input);        

        return response()->json([
            "success" => true,
            "message" => "Tipo de visitante actualizado correctamente.",
            "data" => $tipoVisitante,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoVisitante  $tipoVisitante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipoVisitante = TipoVisitante::findOrFail($id);
        $tipoVisitante->delete();

        return response()->json([
            "success" => true,
            "message" => "Tipo de visitante eliminado correctamente.",
            "data" => $tipoVisitante,
        ]);
    }
}
