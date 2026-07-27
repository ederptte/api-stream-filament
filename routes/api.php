<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Cuando alguien envíe datos a /api/register, lo atiende el controlador
Route::post('/register', [AuthController::class, 'register']);


