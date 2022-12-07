<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{

    public function findByUsernameAndPassword(Request $request)
    {
        
            
        $validator = Validator::make($request->all(), [                        
            'username' => ['required'],
            'password' => ['required'],            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $request['password'] = md5($request->password);
        error_log($request->username);
        $usuario = Usuario::where('usuario', $request->username)
                    ->where('contrasenia', $request->password)
                    ->where('activo', 1)->get();
        $usuario->count() ? $login = true : $login = false;

        return response()->json([
            "error" => $login,
            /* "message" => "Usuario logeado correctamente.", */
            "usr" => $usuario->first(),
            "login" => $login,
        ]);
      

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::all();

        return response()->json([
            'success' => true,
            'message' => 'Listado de usuarios.',
            'data' => $usuarios,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [            
            'nombre' => ['required'],
            'usuario' => ['required'],
            'contrasenia' => ['required'],
            'dui' => ['required'],
            'tipo' => ['required'],
            'activo' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $usuario = Usuario::create($input);

        return response()->json([
            "success" => true,
            "message" => "Usuario creado correctamente.",
            "data" => $usuario,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        if (is_null($usuario)) {
            return response()->json('Usuario no encontrado.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            "success" => true,
            "message" => "Usuario encontrado.",
            "data" => $usuario,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *     
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [            
            'nombre' => ['required'],
            'usuario' => ['required'],
            'contrasenia' => ['required'],
            'dui' => ['required'],
            'tipo' => ['required'],
            'activo' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $usuario = Usuario::findOrFail($id);
        $usuario->update($input);

        return response()->json([
            "success" => true,
            "message" => "Usuario actualizado correctamente.",
            "data" => $usuario,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            "success" => true,
            "message" => "Usuario eliminado correctamente.",
            "data" => $usuario,
        ]);
    }
}
