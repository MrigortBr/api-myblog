<?php

namespace App\Utils;

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class RequestUtility
{
    private $sizeOfPassword;
    private $sizeOfName;
    private $minSizeTitle;
    private $maxSizeTitle;
    private $minSizeContent;
    private $messages;
    private $validationRules;
    private $minSizeComment;
    private $maxSizeComment;


    public function __construct()
    {
        $this->sizeOfPassword = intval(env('sizeOfPassword'));
        $this->sizeOfName = intval(env('sizeOfName'));
        $this->minSizeTitle = intval(env('minSizeTitle'));
        $this->maxSizeTitle = intval(env('maxSizeTitle'));
        $this->minSizeContent = intval(env('minSizeContent'));
        $this->minSizeComment = intval(env('maxSizeComment'));
        $this->maxSizeComment = intval(env('minSizeComment'));
        $this->messages = [];
        $this->validationRules = [];
    }

    public function validatePostCreate(Request $request){
        $this->setMessagesByPost();
        $response = $this->validateParamsRequest($request);
        return $response;
    }

    public function validatePostUpdate(Request $request){
        $this->setMessagesByPostUpdate();
        $response = $this->validateParamsRequest($request);
        return $response;
    }

    public function validateComment(Request $request){
        $this->setMessagesByComments();
        $response = $this->validateParamsRequest($request);
        return $response;
    }



    /**
     * Valida os parâmetros da requisição e finaliza a resposta se houver erro de validação
     *
     * @param Request $request
     * @return AuthCredentials
     */
    public function validateLogin(Request $request){
        $this->setMessagesByLogin();
        return $this->validateParamsRequest($request);
    }

    /**
     * Valida os parâmetros da requisição e finaliza a resposta se houver erro de validação
     *
     * @param Request $request
     * @return AuthCredentials
     */
    public function validateRegister(Request $request){
        $this->setMessagesByRegister();
        $response = $this->validateParamsRequest($request);
        return $response;
    }

    private function validateParamsRequest(Request $request){
        try {
            // Realiza a validação
            return $request->validate($this->validationRules, $this->messages);
        } catch (ValidationException $e) {
            response()->json([
                'message' => 'Requisição mal informada.',
                'errors' => $e->errors(),
            ], 400)->send();
            return '';
        }
    }

    private function setMessagesByPost(){
        $this->validationRules['title'] = ['required', 'string', 'min:'. $this->minSizeTitle.'', 'max:'. $this->maxSizeTitle.''];
        $this->validationRules['content'] = ['required', 'string', 'min:'. $this->minSizeContent.''];
        $this->validationRules['status'] = ['required', 'in:published,draft'];
        $this->validationRules['category'] = ['required', 'string'];


        $this->messages['title.required'] = 'O título é obrigatório.';
        $this->messages['title.string'] = 'O título deve ser uma string.';
        $this->messages['title.min'] = 'O título deve ter no mínimo '. $this->minSizeTitle.' caracteres.';
        $this->messages['title.max'] = 'O título deve ter no máximo '. $this->maxSizeTitle.' caracteres.';
        $this->messages['content.required'] = 'O conteúdo é obrigatório.';
        $this->messages['content.string'] = 'O conteúdo deve ser uma string.';
        $this->messages['content.min'] = 'O conteúdo deve ter no mínimo '. $this->minSizeContent.' caracteres.';
        $this->messages['status.required'] = 'O status é obrigatório.';
        $this->messages['status.in'] = "O status deve ser 'published' ou 'draft'.";
        $this->messages['category.required'] = 'A categoria do post precisa ser informada.';
        $this->messages['category.string'] = 'A categoria deve ser uma string.';
    }

    private function setMessagesByPostUpdate(){
        $this->setMessagesByPost();
        $this->validationRules['title'] = ['string', 'min:'. $this->minSizeTitle.'', 'max:'. $this->maxSizeTitle.'', 'nullable'];
        $this->validationRules['content'] = [ 'string', 'min:'. $this->minSizeContent.'', 'nullable'];
        $this->validationRules['status'] = ['in:published,draft', 'nullable'];
        $this->validationRules['category'] = ['string', 'nullable'];
    }

    private function setMessagesByComments(){
        $this->validationRules['content'] = ['required','string', 'min:'. $this->minSizeComment.'', 'max:'. $this->maxSizeComment.''];

        $this->messages['content.required'] = 'o comentario deve ser preenchido.';
        $this->messages['content.string'] = 'O comentario deve ser uma string.';
        $this->messages['content.min'] = 'O comentario deve ter no mínimo '. $this->minSizeComment.' caracteres.';
        $this->messages['content.max'] = 'O comentario deve ter no máximo '. $this->maxSizeComment.' caracteres.';

    }

    private function setMessagesByLogin(){
        $this->validationRules['email'] = ['required', 'email'];
        $this->validationRules['password'] = ['required', 'min:' . $this->sizeOfPassword];

        $this->messages['email.required'] = 'O campo de e-mail é obrigatório.';
        $this->messages['email.email'] = 'O e-mail fornecido não é válido.';
        $this->messages['password.required'] = 'O campo de senha é obrigatório.';
        $this->messages['password.min'] = 'A sua senha deve ter pelo menos ' . $this->sizeOfPassword . ' caracteres.';
    }

    private function setMessagesByRegister(){
        $this->setMessagesByLogin();

        $this->validationRules['name'] = ['required', 'string', 'min:' . $this->sizeOfName];

        $this->messages['name.required'] = 'O Campo nome é obrigatório';
        $this->messages['name.min'] = 'O seu nome deve ter pelo menos ' . $this->sizeOfName . ' caracteres';

    }

    public function getUserFromToken(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(['message' => 'Token não fornecido'], 401);
            }

            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken) {
                return response()->json(['message' => 'Token inválido ou expirado'], 401);
            }

            $user = $accessToken->tokenable;

            return $user;

        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro inesperado'], 500);
        }
    }
}


