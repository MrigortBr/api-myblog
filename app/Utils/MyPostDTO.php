<?php
namespace App\Utils;

use App\Models\Posts;
use App\Models\Categories;
use App\Models\Comments;

class MyPostDTO{
    public $id;
    public $title;
    public $content;
    public $category;
    public $published_at;
    public $created_at;
    public $updated_at;
    public $comments;

    public function __construct($post, $comments)
    {
        $this->id = $post['id'];
        $this->title = $post['title'];
        $this->content = $post['content'];
        $this->category = $post['name'];
        $this->published_at = $post['published_at'];
        $this->created_at = $post['created_at'];
        $this->updated_at = $post['updated_at'];

        $this->comments = $comments;
    }
}
