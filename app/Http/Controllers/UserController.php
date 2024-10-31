<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioNameUpdate;
use App\Http\Requests\UsuarioPasswordReset;
use App\Http\Requests\UsuarioPasswordSaveReset;
use App\Http\Requests\UsuarioPasswordUpdate;
use Exception;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

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
        $exists = User::withTrashed()->where('dni', $dni)->exists();
        return response()->json(['exists' => $exists]);
    }
    public function checkCorreoExists($correo){
        $exists = User::withTrashed()->where('correo', $correo)->exists();
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

    public function resetPassword(UsuarioPasswordReset $request)
    {
        try {
            $correo = $request->input('correo');
            $user = User::where('correo', $correo)->firstOrFail();
            $token = Str::random(60);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $correo],
                ['token' => $token, 'created_at' => now()]
            );
            //Mail::to($correo)->send(new PasswordResetMail($token));
            //DEBERÍA USAR API_FRONT .ENV ?
            $frontendUrl = "http://localhost:5173/reset-password/$token";
            Mail::to($correo)->send(new PasswordResetMail($frontendUrl));



            return response()->json(['message' => 'Se ha enviado un enlace de restablecimiento a su correo'], 200);
        } catch (Exception $exception) {
            return response()->json(['message' => 'No se encontró ningún usuario con este correo', 'error' => $exception->getMessage()], 404);
        }
    }

    public function showResetForm($token)
    {
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();
        if (!$passwordReset || Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Token inválido o expirado.'], 404);
        }
        return response()->json(['message' => 'Token válido.'], 200);
    }

    // public function updatePasswordReset(UsuarioPasswordSaveReset $request)
    // {
    //     $passwordReset = DB::table('password_resets')
    //         ->where('token', $request->token)
    //         ->where('email', $request->correo)
    //         ->first();

    //     if (!$passwordReset) {
    //         return response()->json(['message' => 'Token o correo inválido.'], 404);
    //     }
    //     $user = User::where('correo', $request->correo)->first();

    //     if (!$user) {
    //         return response()->json(['message' => 'Usuario no encontrado.'], 404);
    //     }

    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     DB::table('password_resets')->where('email', $request->correo)->delete();

    //     return response()->json(['message' => 'Contraseña actualizada correctamente.'], 200);
    // }

    public function updatePasswordReset(UsuarioPasswordSaveReset $request)
    {
        $passwordReset = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Token inválido.'], 404);
        }
        $user = User::where('correo', $passwordReset->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_resets')->where('email', $passwordReset->email)->delete();
        return response()->json(['message' => 'Contraseña actualizada correctamente.'], 200);
    }
}
