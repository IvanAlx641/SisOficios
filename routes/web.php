<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Esto buscará el archivo resources/views/dashboard.blade.php
    return view('dashboard'); 
});
