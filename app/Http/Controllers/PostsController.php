<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\PostsService as ServicePostsService;

class PostsController extends Controller
{

    protected $postsService;

    public function __construct(ServicePostsService $postsService){
        $this->postsService =  $postsService;
    }

    public function create(Request $request) {
        $this->postsService->create($request);
    }

    public function show(Request $request) {
        $this->postsService->show($request);
    }

    public function listById(Request $request, $id){
        $this->postsService->listById($request, $id);
    }

    public function listMyPosts(Request $request){
        $this->postsService->listMyPosts($request);
    }

    public function update(Request $request, $id){
        $this->postsService->update($request, $id);
    }

    public function delete(Request $request, $idPost){
        $this->postsService->delete($request, $idPost);
    }


}
