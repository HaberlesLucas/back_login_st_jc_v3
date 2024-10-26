<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UsuarioLoginRequest;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Exception;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    public function setUsuario(UsuarioRequest $usuarioRequest)
    {
        $data = $usuarioRequest->validated();
        $roles = $data['roles'];
        unset($data['roles']);
        $data['password'] = bcrypt($data['password']);

        DB::beginTransaction();
        try {
            $newUsuario = User::create($data);

            if (!$newUsuario) {
                throw new Exception('No se pudo crear el usuario.');
            }

            if (!empty($roles)) {
                $rolesData = collect($roles)->map(function ($rolId) use ($newUsuario) {
                    return ['dni' => $newUsuario->dni, 'id_rol' => $rolId];
                })->toArray();

                DB::table('rol_user')->insert($rolesData);
            }

            DB::commit();
            $newUsuarioWithRoles = User::with('roles:id_rol,nombre')
                ->find($newUsuario->dni)
                ->makeHidden(['created_at', 'updated_at', 'deleted_at']);
            $rolesFormatted = $newUsuarioWithRoles->roles->map(function ($role) {
                return [ //preparo el json para roles. quito 'created_at', 'updated_at', 'deleted_at'
                    'id_rol' => $role->id_rol,
                    'nombre' => $role->nombre,
                ];
            });
            $newUsuarioWithRoles->roles = $rolesFormatted;

            return $this->sendResponse($newUsuarioWithRoles, 'Usuario creado y roles asignados correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError(
                'Ocurrió un error al crear el usuario',
                ['error' => $e->getMessage()],
                500
            );
        }
    }

    public function login(UsuarioLoginRequest $usuarioRequest)
    {
        // dd($usuarioRequest)->only();
        $credenciales = $usuarioRequest->only('dni', 'password');
        if (! $token = JWTAuth::attempt($credenciales)) {
            //dd("ss");
            return response()->json(['error' => 'No autorizado'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        // dd($token);
        // exit;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ], 200);
    }

    public function profile()
    {
        $success = JWTAuth::user();
        return $this->sendResponse($success, 'búsqueda de perfil realizada con éxito');
    }

    public function refresh()
    {
        $success = $this->respondWithToken(JWTAuth::refresh());
        return $this->sendResponse($success, 'token actualizado correctamente');
    }

    public function logout()
    {
        // $success = auth()->logout();
        $success = JWTAuth::logout();
        return $this->sendResponse($success, 'sesión cerrada correctamente');
    }
}
