<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    //Контроль авторизованных пользователей
    public function IndexPage() {

        $user = ['isLoggedin' => false];
        return view('app', ['user' => $user]);
     
    }

}
