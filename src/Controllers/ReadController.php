<?php

namespace Ciber2018\Tabletoscript\Controllers;

use Illuminate\Http\Request;

class ReadController{
    public function index()
    {
        return view('tabletoscript::index');       
    }
}