<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Unit;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function unauthorized()
    {
        return \response()->json([
            'error' => 'Não autorizado!',
        ], 401);
    }

    public function register(RegisterRequest $request)
    {
        $array = [
            'error' => ''
        ];

        DB::beginTransaction();

        try {

            $cpf = str_replace(['.', '-', '/'], '', $request->cpf);
            $pass = \password_hash($request->password, \PASSWORD_DEFAULT);

            $user = new User();
            $user->name = $request->name;
            $user->cpf = $cpf;
            $user->email = $request->email;
            $user->password = $pass;
            if (!$user->save()) {
                throw new Exception("Erro ao registrar usuário!");
            }

            $token = auth()->attempt([
                'cpf' => $cpf,
                'password' => $request->password,
            ]);

            if (!$token) {
                $array['error'] = 'Usuário e/ou Senha Inválidos!';

                return \response()->json($array);
            }

            $array['token'] = $token;

            $user = auth()->user();
            $array['user'] = $user;

            $properties = Unit::select(['id', 'name'])->where('id_owner', $user['id'])->get();
            $array['user']['properties'] = $properties;

            DB::commit();

            return \response()->json($array);
        } catch (Exception $error) {
            DB::rollBack();

            $array['error'] = $error;

            return \response()->json($array);
        }
    }

    public function login(LoginRequest $request)
    {
        $array = [
            'error' => ''
        ];

        $cpf = str_replace(['.', '-', '/'], '', $request->cpf);

        $token = auth()->attempt([
            'cpf' => $cpf,
            'password' => $request->password,
        ]);

        if (!$token) {

            $array['error'] = 'Usuário e/ou Senha Inválidos!';

            return \response()->json($array);
        }

        $array['token'] = $token;

        $user = auth()->user();
        $array['user']['name'] = $user->name;
        $array['user']['email'] = $user->email;
        $array['user']['cpf'] = $user->cpf;

        $properties = Unit::select(['id', 'name'])->where('id_owner', $user['id'])->get();
        $array['user']['properties'] = $properties;

        return response()->json($array);
    }

    public function validateToken()
    {
        $array = [
            'error' => ''
        ];

        $user = auth()->user();
        $array['user']['name'] = $user->name;
        $array['user']['email'] = $user->email;
        $array['user']['cpf'] = $user->cpf;

        $properties = Unit::select(['id', 'name'])->where('id_owner', $user['id'])->get();
        $array['user']['properties'] = $properties;

        return response()->json($array);
    }

    public function logout()
    {
        $array = [
            'error' => ''
        ];

        auth()->logout();

        $array['msg'] = 'Usuário deslogado!';

        return response()->json($array);
    }
}
