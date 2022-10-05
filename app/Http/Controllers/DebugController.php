<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Storage; 

class DebugController extends Controller
{
    function debugPost(Request $request) {
        $request->file('kim')
        ->storeAs('qwerty','kimTest.jpg');
    }
    function debug() {
        $user = new User;
        $id = 28;
        $user -> endExam($id);
        $a = $user->hasActiveTest($id);
        $b = $user->activeTestId($id);
        var_dump($a,$b);
    }
}
