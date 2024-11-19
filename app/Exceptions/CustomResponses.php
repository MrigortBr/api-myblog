<?php

namespace App\Exceptions;

use Exception;

class CustomResponses extends Exception
{
    public static function LogoutInvalidToken()
    {
        return response()->json(["message" => "Ocorreu um erro ao invalidar o token. Tente novamente mais tarde."], 400)->send();
    }

    public static function LogoutSucess()
    {
        return response()->json(["message" => "Token invalidado com sucesso!"], 200)->send();
    }

    public static function NoTokenFound()
    {
        return response()->json(["message" => "Usuário não autenticado"], 401)->send();
    }

    public static function ExceptionError()
    {
        return response()->json(["message" => "Ops, aconteceu um erro inesperado. Tente novamente mais tarde."], 500)->send();
    }

    public static function LoginSucess($token)
    {
        return response()->json(["message" => "Login realizado com sucesso", 'token'=> $token], 200)->send();
    }

    public static function CredentialsInvalid()
    {
        return response()->json(["message" => "Credenciais inválidas"], 401)->send();
    }

    public static function AccountCreated($token)
    {
        return response()->json(["message" => "Conta criada com sucesso", 'token' => $token], 200)->send();
    }

    public static function AccountExists()
    {
        return response()->json(["message" => "Já existe uma conta cadastrada com esse e-mail."], 409)->send();
    }

    public static function QueryException()
    {
        return response()->json(["message" => "Erro ao acessar o banco de dados. tente novamente mais tarde"], 409)->send();
    }

    public static function PostCreated($idPost)
    {
        return response()->json(["message" => "Post criado com sucesso", 'link' => env('APP_URL') .'/post/'.$idPost], 200)->send();
    }

    public static function ShowPosts($dataResponse)
    {
        return response()->json($dataResponse, 200)->send();
    }

    public static function NoCategory()
    {
        return response()->json(["message" => "Não existe esta categoria."], 200)->send();
    }

    public static function NoPosts()
    {
        return response()->json(["message" => "Não existe posts, tente mudar a categoria."], 200)->send();
    }

    public static function YouNoHavePost(){
        return response()->json(["message" => "Você não tem posts criados."], 200)->send();
    }

    public static function UnablePost(){
        return response()->json(["message" => "Post não disponivel."], 200)->send();
    }

    public static function PostUpdated(){
        return response()->json(["message" => "Post atualizado com sucesso."], 200)->send();
    }

    public static function PostNoUpdated(){
        return response()->json(["message" => "Nenhuma alteração foi feita no post."], 200)->send();
    }

    public static function PostDeleted(){
        return response()->json(["message" => "Post deletado com sucesso."], 200)->send();
    }

    public static function CommentCreated(){
        return response()->json(["message" => "Comentario feito com sucesso."], 200)->send();
    }

    public static function CommentDeleted(){
        return response()->json(["message" => "Comentario deletado com sucesso."], 200)->send();
    }

    public static function NoComments(){
        return response()->json(["message" => "Sem comentarios."], 200)->send();
    }

    public static function UnableComments(){
        return response()->json(["message" => "Comentario não disponivel."], 200)->send();
    }

    public static function EditCommentsSucess(){
        return response()->json(["message" => "Comentario Atualizado com sucesso."], 200)->send();
    }






}
