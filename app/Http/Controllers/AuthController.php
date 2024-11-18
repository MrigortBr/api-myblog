<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utils\AuthUtilityClass;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    protected $authUtility;

    public function __construct(AuthUtilityClass $authUtility)
    {
        $this->authUtility = $authUtility;
    }

    public function register(Request $request) {
        try {
            $credentials = $this->authUtility->validateParamsAuth($request);

            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);

            return response()->json([
                'message' => 'Conta criada com sucesso',
                'next' => 'Para realizar o login, entre na rota de login ' . env('APP_URL') . '/login',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 400);
        } catch (QueryException $sql) {
            if ($sql->errorInfo[1] == 1062) {
                return response()->json([
                    'message' => 'Conta já cadastrada com esse e-mail.',
                ], 409);
            }

            return response()->json([
                'message' => 'Erro ao acessar o banco de dados.',
                'error' => $sql->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocorreu um erro inesperado.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request){
        try {
            $credentials = $this->authUtility->validateParamsAuth($request, true);

            if (Auth::attempt($credentials)){
                $user = Auth::user();
                $token = $user->createToken("userToken")->plainTextToken;

                return response()->json([
                    'message' => 'Login realizado com sucesso',
                    'token' => $token,
                ]);
            }else{
                return response()->json([
                    'message' => 'Credenciais inválidas',
                ], 401);
            }


        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 400);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function logout(Request $request){
        try {
            if ($request->user()) {
                $request->user()->tokens()->delete();

                return response()->json(["message" => "Token invalidado com sucesso!"], 200);
            }

            return response()->json(["message" => "Usuário não autenticado"], 401);

        } catch (QueryException $e) {
            return response()->json([
                "message" => "Ocorreu um erro ao invalidar o token. Tente novamente mais tarde."
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Ops, aconteceu um erro inesperado. Tente novamente mais tarde."
            ], 500);
        }
    }

    public function getUserFromToken(Request $request)
    {
        try {
            // Obtém o token do cabeçalho da requisição
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(['message' => 'Token não fornecido'], 401);
            }

            // Verifica se o token está presente na tabela `personal_access_tokens`
            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken) {
                return response()->json(['message' => 'Token inválido ou expirado'], 401);
            }

            // Obtém o usuário associado ao token
            $user = $accessToken->tokenable;

            return $user;

        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro inesperado'], 500);
        }
    }

}
