<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){
        $credenciais = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credenciais);

        if($token){
            return response()->json(['token' => $token]);
        }else{
            return response()->json(['erro' => 'Usuário ou senha inválido'], 403);
        }

        return 'login';
    }

    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso']);
    }

    public function reset(Request $request, User $user){
        $usuario = $user->find(Auth::user()->id);
        $usuario->password = bcrypt($request->newPassword);
        $usuario->save();

        return response()->json(['msg' => 'Senha alterada com sucesso']);
    }
}
