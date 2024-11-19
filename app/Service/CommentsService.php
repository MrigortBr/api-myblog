<?php
namespace App\Service;

use App\Exceptions\CustomResponses;
use App\Models\Comments;
use App\Models\Posts;
use App\Utils\CommentDTO;
use App\Utils\CommentsDTO;
use App\Utils\MyCommentsDTO;
use App\Utils\RequestUtility;
use Illuminate\Http\Request;

class CommentsService{

    protected $requestUtility;

    public function __construct( RequestUtility $requestUtility){
        $this->requestUtility =  $requestUtility;
    }

    public function create(Request $request, $idPost){
        $data = $this->requestUtility->validateComment($request);
        if (!$data) return;

        $post = Posts::find($idPost);
        $user = $this->requestUtility->getUserFromToken($request);

        if (json_encode($post) === 'null'){
            return CustomResponses::UnablePost();
        }

        Comments::create([
            'post_id'=>$idPost,
            'user_id'=>$user['id'],
            'content'=>$data["content"]
        ]);

        return CustomResponses::CommentCreated();
    }

    public function myComments(Request $request){
        $myComments = $this->getMyComments($request);

        if (json_encode($myComments) === '[]'){
            return CustomResponses::NoComments();
        }else{
            return response()->json($myComments)->send();
        }
    }

    public function listById(Request $request, $idComment){
        $comment = Comments::where('comments.id',$idComment)->join('users', 'users.id', '=', 'comments.user_id')->first();
        $myID =  AuthService::getUserIdFromToken($request);

        if (json_encode($comment) === 'null'){
            return CustomResponses::NoComments();
        }else{
            return response()->json(new CommentDTO($comment, $myID ))->send();
        }
    }

    public function update(Request $request, $idComment){
        $data = $this->requestUtility->validateComment($request);
        if (!$data) return;

        $comment = Comments::find($idComment);
        $myID =  AuthService::getUserIdFromToken($request);

        if (json_encode($comment) !== 'null'){
           if($comment['user_id'] == $myID){
            $comment['content'] = $data['content'];
            $comment->save();
            return CustomResponses::EditCommentsSucess();
           }
        }
        return CustomResponses::UnableComments();
    }

    public function delete(Request $request, $idComment){
        $comment = Comments::find($idComment);

        if (!$comment) {
            return CustomResponses::UnableComments();
        }

        if (!$this->iCanChangeThisComment($request, $comment)) return CustomResponses::UnablePost();

        $comment->delete();
        return CustomResponses::CommentDeleted();
    }

    private function iCanChangeThisComment($request ,$comment){
        $user = $this->requestUtility->getUserFromToken($request);
        if ($comment->user_id !== $user->id) {
            return false;
        }else{
            return true;
        }

    }

    public static function getCommentsByPost(Request $request, $idPost){
        $comments =  Comments::
        where("post_id", $idPost)
        ->join('users', 'users.id', '=', 'comments.user_id')
        ->select('comments.*', 'users.name')
        ->get();

        $commentsDTOs = [];
        $myID =  AuthService::getUserIdFromToken($request);

        foreach ($comments as $comment) {
            $commentsDTOs[] = new CommentsDTO($comment, $myID);
        }

        return $commentsDTOs;
    }

    public function getMyComments(Request $request){
        $myID =  AuthService::getUserIdFromToken($request);

        $comments =  Comments::
        where("user_id", $myID)
        ->select('comments.*')
        ->get();

        $commentsDTOs = [];

        foreach ($comments as $comment) {
            $commentsDTOs[] = new MyCommentsDTO($comment);
        }

        return $commentsDTOs;
    }


    }
