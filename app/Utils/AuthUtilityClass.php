<?php

namespace App\Utils;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthUtilityClass
{

    /**
     * @param Request $request
     * @return AuthCredentials
     */
    public function validateParamsAuth(Request $request, bool $isLogin = false)
    {
        // Tamanho mínimo definido no .env
        $sizeOfName = intval(env('sizeOfName'));
        $sizeOfPassword = intval(env('sizeOfPassword'));

        // Definir as regras de validação
        $validationRules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:' . $sizeOfPassword],
        ];

        // Se não for login, adicionar a validação de 'name'
        if (!$isLogin) {
            $validationRules['name'] = ['required', 'string', 'min:' . $sizeOfName];
        }

        // Mensagens personalizadas de erro
        $messages = [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'O e-mail fornecido não é válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.min' => 'A sua senha deve ter pelo menos ' . $sizeOfPassword . ' caracteres.',
        ];

        // Adicionar mensagens de erro para o campo 'name', se necessário
        if (!$isLogin) {
            $messages['name.required'] = 'O Campo nome é obrigatório';
            $messages['name.min'] = 'O seu nome deve ter pelo menos ' . $sizeOfName . ' caracteres';
        }

        try {
            // Realizar a validação
            return $request->validate($validationRules, $messages);
        } catch (ValidationException $e) {
            // Tratamento de erro de validação
            throw new ValidationException($e->validator, response()->json([
                'message' => 'Erro de validação ao tentar autenticar.',
                'errors' => $e->errors(),
                'details' => $e->getMessage(), // Adiciona detalhes sobre o erro
            ], 400));
        }
    }
}


