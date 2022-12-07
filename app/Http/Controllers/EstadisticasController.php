<?php

namespace App\Http\Controllers;

use App\Models\Estadisticas;
use App\Models\Parques;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EstadisticasController extends Controller
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

        $estadisticas = Parques::select('parques.id', 'parques.nombre','tipovisitante.tipo', DB::raw('IFNULL(SUM(estadisticas.cantidad), 0) AS cantidad'))
                        ->join('usuario_parque', 'usuario_parque.id_parque', 'parques.id')
                        ->leftJoin('estadisticas', 'estadisticas.id_parque', 'parques.id')
                        ->leftJoin('tipovisitante', 'tipovisitante.id', 'estadisticas.tipo')
                        ->where('usuario_parque.id_usuario', $id)
                        ->whereBetween('estadisticas.fecha', 
                        [DB::raw("STR_TO_DATE('$inicio', '%d-%m-%Y')"), DB::raw("STR_TO_DATE('$fin', '%d-%m-%Y')")])
                        ->groupBy('parques.id')
                        ->groupBy('parques.nombre')
                        ->groupBy('tipovisitante.tipo')
                        ->orderBy('cantidad')->get();

        return response()->json([
            'success' => true,
            'message' => 'Estadística generada.',
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
        $estadisticas = Estadisticas::all();

        return response()->json([
            'success' => true,
            'message' => 'Listado de estadísticas.',
            'data' => $estadisticas,
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
            'id_parque' => ['required', 'integer'],
            'tipo'  => ['required', 'integer'],
            'fecha'  => ['required', 'date'],
            'cantidad'  => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $estadistica = Estadisticas::create($input);

        return response()->json([
            "success" => true,
            "message" => "Estadística creada correctamente.",
            "data" => $estadistica,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estadisticas  $estadisticas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estadistica = Estadisticas::findOrFail($id);
        
        if (is_null($estadistica)) {
            return response()->json('Estadística no encontrada.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            "success" => true,
            "message" => "Estadística encontrado.",
            "data" => $estadistica,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estadisticas  $estadisticas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id_parque' => ['required', 'integer'],
            'tipo'  => ['required', 'integer'],
            'fecha'  => ['required', 'date'],
            'cantidad'  => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $estadistica = Estadisticas::findOrFail($id);
        $estadistica->update($input);

        return response()->json([
            "success" => true,
            "message" => "Estadística actualizada correctamente.",
            "data" => $estadistica,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estadisticas  $estadisticas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estadistica = Estadisticas::findOrFail($id);
        $estadistica->delete();

        return response()->json([
            "success" => true,
            "message" => "Estadística eliminada correctamente.",
            "data" => $estadistica,
        ]);
    }    
}
