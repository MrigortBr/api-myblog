<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function create() {
        return response('Olá mundo, "Create"');
    }

    public function show() {
        return response('Olá mundo, "Show"');
    }

    public function read(){
        return response('Olá mundo');
    }

    public function update(){
        return response('Olá mundo, "Update"');
    }

    public function delete(){
        return response('Olá mundo, "Delete"');
    }
}
