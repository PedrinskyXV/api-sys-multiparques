<?php

namespace App\Http\Controllers;

use App\Models\Parques;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ParquesController extends Controller
{
    public function obtener(Request $request)
    {                
        $validator = Validator::make($request->all(), [
            'id_user' => ['required'],
            'fechaDesde'  => ['required'],
            'fechaHasta'  => ['required'],            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $id = $request->id_user;
        $inicio = $request->fechaDesde;
        $fin = $request->fechaHasta;

        $estadisticas = Parques::select('parques.id as id_park', 'parques.nombre', 'estadisticas.fecha', 
                        DB::raw('IFNULL(SUM(estadisticas.cantidad), 0) AS cantidad'), 'tipovisitante.tipo')
                        ->join('usuario_parque', 'usuario_parque.id_parque', 'parques.id')
                        ->leftJoin('estadisticas', 'estadisticas.id_parque', 'parques.id')
                        ->leftJoin('tipovisitante', 'tipovisitante.id', 'estadisticas.tipo')
                        ->where('usuario_parque.id_usuario', $id)
                        ->whereBetween('estadisticas.fecha', 
                        [DB::raw("STR_TO_DATE('$inicio', '%d-%m-%Y')"), DB::raw("STR_TO_DATE('$fin', '%d-%m-%Y')")])
                        ->groupBy('parques.nombre', 'estadisticas.fecha', 'tipovisitante.tipo', 'id_park')
                        ->orderBy('estadisticas.fecha')->get();

        return response()->json([
            'success' => true,
            'message' => 'Parques generado.',
            'data' => $estadisticas,
        ]);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parques = Parques::all();

        return response()->json([
            'success' => true,
            'message' => 'Listado de parques.',
            'data' => $parques,
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
            'nombre' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

       $parque = Parques::create($input);

        return response()->json([
            "success" => true,
            "message" => "Parque creado correctamente.",
            "data" =>$parque,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parques  $parques
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $parque = Parques::findOrFail($id);
        
        if (is_null($parque)) {
            return response()->json('Parque no encontrado.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            "success" => true,
            "message" => "Parque encontrado.",
            "data" => $parque,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parques  $parques
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nombre' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $parque = Parques::findOrFail($id);
        $parque->update($input);        

        return response()->json([
            "success" => true,
            "message" => "Parque actualizado correctamente.",
            "data" => $parque,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parques  $parques
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parque = Parques::findOrFail($id);
        $parque->delete();

        return response()->json([
            "success" => true,
            "message" => "Parque eliminado correctamente.",
            "data" => $parque,
        ]);
    }
}
