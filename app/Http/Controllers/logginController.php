<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logginController extends Controller
{
    public function index(){
        return view('login.index');
    }

    public function loginProcess(LoginRequest $request){

        $request ->validated();

        $Authenticate = Auth::attempt(['email'=>$request -> email, 'password'=>$request -> password]);

        if(!$Authenticate){

            return back()->withInput() ->with('erro','Email ou Senha inválida');
        }

        return redirect()->route('index');
    }

    public function destroy(){
        Auth::logout();
        return redirect()->route('login');
    }
}