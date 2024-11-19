<?php
namespace App\Utils;

use App\Models\Posts;
use App\Models\Categories;


class PostTitleDTO{
    public $title;
    public $categoryName;
    public $published_at;
    public $created_at;
    public $updated_at;
    public $link;

    public function __construct(Posts $post, Categories $category)
    {
        $this->title = $post['title'];
        $this->categoryName = $category['name'];
        $this->link = env('APP_URL'). '/post'. $post['id'];
        $this->published_at = $post['published_at'];
        $this->created_at = $post['created_at'];
        $this->updated_at = $post['updated_at'];
    }
}
