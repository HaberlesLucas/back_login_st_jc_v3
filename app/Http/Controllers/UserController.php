<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioNameUpdate;
use App\Http\Requests\UsuarioPasswordUpdate;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{


    public function getUsuarios()
    {
        try {
            // Obtén el usuario logueado
            $loggedInUser = JWTAuth::user();

            // Verifica si hay un usuario logueado
            if (!$loggedInUser) {
                return response()->json(['error' => 'Usuario no autenticado.'], 401);
            }

            // Filtra usuarios excluyendo al usuario logueado
            $users = User::with('roles')
                ->withTrashed()
                ->where('dni', '!=', $loggedInUser->dni) // Filtra el usuario logueado
                ->get();

            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al obtener los usuarios: ' . $e->getMessage()], 500);
        }
    }


    public function getUsuario($dni)
    {
        try {
            $usuario = User::withTrashed()->with('roles')->findOrFail($dni);
            return response()->json($usuario, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al obtener el usuario: ' . $e->getMessage()], 500);
        }
    }

    public function checkDniExists($dni)
    {
        //$exists = User::where('dni', $dni)->exists(); //retorna false o true
        // return response()->json(['exists' => $exists]); //asignar la el 'valor' del metodo exists() a la respuesta
        $exists = User::withTrashed()->where('dni', $dni)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function updateUsuario(UsuarioRequest $usuarioRequest, $dni)
    {
        // $usuario = User::findOrFail($dni);
        $usuario = User::withTrashed()->findOrFail($dni);
        $usuario->update($usuarioRequest->only(['apellido_nombre', 'password', 'estado']));

        if ($usuarioRequest->roles) {
            $usuario->roles()->sync($usuarioRequest->roles);
        }

        return response()->json($usuario->load('roles'), 200);
    }

    public function deleteUsuario($dni)
    {
        try {
            $usuario = User::findOrFail($dni);

            $usuario->estado = 0;
            $usuario->save();

            $usuario->roles()->detach();
            $usuario->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al eliminar el usuario: ' . $e->getMessage()], 500);
        }
    }

    public function restoreUsuario($dni)
    {
        try {
            $usuario = User::withTrashed()->where('dni', $dni)->firstOrFail();

            $usuario->estado = 1;
            $usuario->save();

            $usuario->restore();

            return response()->json(['message' => 'Usuario restaurado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al restaurar el usuario: ' . $e->getMessage()], 500);
        }
    }


    public function getLoggedInUser()
    {
        try {
            // Obtén el usuario desde el token JWT
            $loggedInUser = JWTAuth::user();
            // Verifica si el usuario fue encontrado
            if (!$loggedInUser) {
                return response()->json(['error' => 'Usuario no autenticado.'], 401);
            }
            // Retorna la información del usuario
            return response()->json($loggedInUser, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se encontró el usuario en la base de datos.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al obtener el usuario: ' . $e->getMessage()], 500);
        }
    }

    public function updateName(UsuarioNameUpdate $usuarioNameUpdate)
    {
        $user = JWTAuth::user();

        $user->apellido_nombre = $usuarioNameUpdate->nombre;
        $user->save();

        return response()->json(['message' => 'Nombre actualizado correctamente.'], 200);
    }


    public function updatePassword(UsuarioPasswordUpdate $usuarioPasswordUpdate)
    {
        $user = JWTAuth::user();

        if (!Hash::check($usuarioPasswordUpdate->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual no es correcta.'], 400);
        }

        $user->password = Hash::make($usuarioPasswordUpdate->new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.'], 200);
    }
}
