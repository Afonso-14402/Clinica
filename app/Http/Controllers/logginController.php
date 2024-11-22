<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class logginController extends Controller
{
    public function index(){
        return view('login.index');
    }
}
