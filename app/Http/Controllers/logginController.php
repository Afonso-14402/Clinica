<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LogginController extends Controller
{
    public function index(){
        return view('login.index');
    }

    public function loginProcess(LoginRequest $request){

        dd($request);
        
    }

}