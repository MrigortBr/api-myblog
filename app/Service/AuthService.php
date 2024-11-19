<?php

namespace App\Service;

use App\Exceptions\CustomResponses;
use App\Models\User;
use Illuminate\Http\Request;
use App\Utils\RequestUtility;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService{

    protected $requestUtility;

    public function __construct( RequestUtility $requestUtility){
        $this->requestUtility =  $requestUtility;
    }

    public function login(Request $request){
        $credentials = $this->requestUtility->validateLogin($request);

        if (!$credentials) return;

        if (Auth::attempt($credentials)){
            $user = Auth::user();
            $token = $this->functionGenerateToken($user);
            CustomResponses::LoginSucess($token);
        }else{
            CustomResponses::CredentialsInvalid();
        }
    }

    public function register(Request $request){
        try {
            $credentials = $this->requestUtility->validateRegister($request);
            if (!$credentials) return;
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password']),
            ]);

            $token = $this->functionGenerateToken($user);

            CustomResponses::AccountCreated($token);

        } catch (QueryException $sql) {
            if ($sql->errorInfo[1] == 1062) {
                CustomResponses::AccountExists();
                return;
            }
            CustomResponses::QueryException();

        } catch (\Exception $e) {
            CustomResponses::ExceptionError();
        }
    }

    public function logout(Request $request){
        try{
            $token = $request->bearerToken();
            if ($token) {
                $personalAccessToken = PersonalAccessToken::findToken($token);

                if ($personalAccessToken == null){
                    CustomResponses::NoTokenFound();
                    return;
                }else{
                    $personalAccessToken->delete();
                    CustomResponses::LogoutSucess();
                    return;
                }

            }
            CustomResponses::NoTokenFound();
        }catch(QueryException $QE){
            CustomResponses::LogoutInvalidToken();
        }catch(\Exception $e){
            CustomResponses::ExceptionError();
        }
    }

    private function functionGenerateToken(User $user): string{
        return $user->createToken("userToken")->plainTextToken;
    }

    public static function getUserIdFromToken(Request $request): ?int
    {
        $token = $request->bearerToken();

        try {
            if (!$token) {
                return null; // Token não fornecido
            }

            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken) {
                return null; // Token inválido ou expirado
            }

            // Retorna apenas o ID do usuário
            $user = $accessToken->tokenable;
            return $user->id;

        } catch (\Exception $e) {
            return null; // Erro inesperado, retorna null
        }
    }
}
