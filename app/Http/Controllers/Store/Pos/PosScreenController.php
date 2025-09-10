<?php

namespace App\Http\Controllers\Store\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PosScreenController extends Controller
{
    public function index()
    {
        return view('store.pos.pos-screen');
    }
}
