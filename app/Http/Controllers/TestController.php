<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Validators\JsonValidator;

class TestController extends Controller
{
    function saveAnswer(Request $request) {
        $data = JsonValidator::validate($request);
        if($data['fails']) {
            return response()->json($data['errors'],422);
        }
        $data = json_decode($data['json'],true);
        $task = $data['task'];
        $answer = $data['answer'];
        session()->put("savedAnswers.$task",$answer);
    }

    function getKimTask($number) {
        $email = session('email');
        // $user = new User;
        // $kim = $user->kimName($email);
        $kim = 'debug';
        if(Storage::exists("kims/$kim/$number.png")) {
            return response()->file("../storage/app/kims/$kim/$number.png");
        }
        else {
            return response()->json(['error' => 'Not found'],404);
        }
    }

    function getTask($kim,$number) {
        if(Storage::exists("kims/$kim/$number.png")) {
            return response()->file("../storage/app/kims/$kim/$number.png");
        }
        else {
            return response()->json([],404);
        }
    }
}
