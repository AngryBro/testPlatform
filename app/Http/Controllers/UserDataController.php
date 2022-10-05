<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage; 
use App\Http\Responses\ResponseWithHeader;
use App\Validators\JsonValidator;
use Carbon\Carbon;


class UserDataController extends Controller
{

    function startExam(Request $request) {
        $api_token = json_decode($request->all()['json'],true)['api_token'];
        $user = new User;
        $userId = $user->getIdByToken($api_token);
        if($user->hasActiveTest($userId)) {
            return;
        }
        $user->startExam($userId);
    }

    function endExam(Request $request) {
        $api_token = json_decode($request->all()['json'],true)['api_token'];
        $user = new User;
        $userId = $user->getIdByToken($api_token);
        $user->endExam($userId);
    }

    function downloadFile(Request $request) {
        $params = $request->all();
        $file = $params['file'];
        $api_token = $request->all()['api_token'];
        $user = new User;
        $kim = $user->kimName($api_token);
        if(Storage::exists("kims/$kim/$file")) {
            return Storage::download("kims/$kim/$file");
        }
        return response()->json([],404);
    }

    function getSavedAnswers(Request $request) {
        $user = new User;
        $api_token = $request->all()['api_token'];
        $data = $user->getSavedAnswers($user->getIdByToken($api_token));
        if(empty($data)) {
            return response()->json([],404);
        }
        return response()->json($data);
    }

    function saveAnswers(Request $request) {
        $data = $request->validate([
            'api_token' => 'required'
        ]);
        $api_token = $data['api_token'];
        $user = new User;
        $id = $user->getIdByToken($api_token);
        if($user->hasActiveTest($id)) {
            $user->saveAnswers($data['json'],$id);
        }
    }

    function getKimData(Request $request) {
        // $api_token = 'B1SZrKI6Yn5dT2dwNT5xi1BNUbcNY6RyVvRIuuiRHMMTI4QdfI1yGipxVsY7Q5Y6NgXr0lPXxT1hMtgZ';
        $api_token = $request->all()['api_token'];
        $user = new User;
        $kim = $user->kimName($api_token);
        if($kim===null) {
            return response()->json([],404);
        }
        $fullData = Storage::get("kims/$kim/data.json");
        $fullData = json_decode($fullData,true);
        $data = [];
        foreach($fullData as $task => $allData) {
            $data[$task] = ['files' => $allData['files']];
        }
        return response()->json($data);//->header('Access-Control-Allow-Origin','*');
    }

    function getKimTask(Request $request) {
        $data = $request->all();
        $api_token = $data['api_token'];
        $user = new User;
        $number = $data['task'];
        $kim = $user->kimName($api_token);
        if($kim===null) {
            return response()->json([],404);
        }
        if(Storage::exists("kims/$kim/$number.png")) {
            return response()->file("../storage/app/kims/$kim/$number.png");
        }
        else {
            return response()->json([],404);
        }
    }

    function getEmailsAndKims() {
        $user = new User;
        $data = $user->emailsAndKims();
        return response()->json($data);//->header('Access-Control-Allow-Origin','*');
    }
}
