<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Posts;
use App\Service\CommentsService;
use App\Utils\RequestUtility;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    protected $commentsService;

    public function __construct( CommentsService $commentsService){
        $this->commentsService =  $commentsService;
    }

    public function comment(Request $request, $idPost){
        $this->commentsService->create($request, $idPost);
    }

    public function listMyComments(Request $request){
        $this->commentsService->myComments($request);
    }

    public function listById(Request $request, $idComment){
        $this->commentsService->listById($request, $idComment);
    }

    public function update(Request $request, $idComment){
        $this->commentsService->update($request, $idComment);
    }

    public function delete(Request $request, $idComment){
        $this->commentsService->delete($request, $idComment);
    }

}
