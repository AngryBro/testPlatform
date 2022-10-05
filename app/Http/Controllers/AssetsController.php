<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetsController extends Controller
{
    function asset($file) {
        $extension = explode('.',$file);
        $extension = $extension[1];
        $path = "../resources/$extension/$file";
        return response()->file($path);
    }
}
