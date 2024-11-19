<?php

namespace App\Utils;

use App\Models\User;
use App\Models\Comments;

class CommentDTO
{
    public $id;
    public $content;
    public $name;
    public $created_at;
    public $updated_at;
    public $linkPost;

    public function __construct(Comments $comments, $myID)
    {
        $this->id = $comments['id'];
        $this->content =$comments['content'];

        if ($comments['user_id'] == $myID){
            $this->name = "Você.";
        }else{
            $this->name = $comments['name'];
        }
        $this->created_at = $comments['created_at'];
        $this->updated_at = $comments['updated_at'];
        $this->linkPost = env('APP_URL') .'/post/'.$comments['post_id'];
    }
}