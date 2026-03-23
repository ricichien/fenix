<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api-docs.yaml', function () {
    $path = base_path('docs/api.yaml');

    if (!File::exists($path)) {
        abort(404, 'API documentation not found');
    }

    return response(File::get($path), 200)
        ->header('Content-Type', 'text/yaml');
});
