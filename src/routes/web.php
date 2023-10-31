<?php

use Ciber2018\Tabletoscript\Controllers\ReadController;
use Illuminate\Support\Facades\Route;

Route::get('table',[ReadController::class,'index']);
Route::post('process',[ReadController::class,'read']);