<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Service\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(Request $request) {
        $this->service->register($request);
    }

    public function login(Request $request){
        $this->service->login($request);
    }

    public function logout(Request $request){
        $this->service->logout($request);
    }
}
