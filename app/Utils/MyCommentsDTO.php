<?php

namespace App\Utils;

use App\Models\User;
use App\Models\Comments;

class MyCommentsDTO
{
    public $id;
    public $content;
    public $created_at;
    public $updated_at;
    public $link;


    public function __construct(Comments $comments)
    {
        $this->id = $comments['id'];
        $this->content =$comments['content'];
        $this->link = env('APP_URL') .'/post/'.$comments['post_id'];
        $this->created_at = $comments['created_at'];
        $this->updated_at = $comments['updated_at'];
    }
}
