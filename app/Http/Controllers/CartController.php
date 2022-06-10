<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCart(){
        if(Auth::check() && Auth::user()->hasRole(['admin', 'superadmin']))
            return redirect()->route('home');

        return view('all.cart');

    }
}
