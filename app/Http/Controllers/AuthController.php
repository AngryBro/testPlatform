<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\User;

class AuthController extends Controller
{
    function login(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => 'Отсутствует Email',
            'email.email' => 'Некорректный Email',
            'password.required' => 'Отсутствует пароль'
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors()->toArray(),422);
        }
        $data = $validator->validated();
        $user = new User;
        $email = $data['email'];
        $password = $data['password'];
        $loginResponse = $user->login($email,$password);
        $logged = $loginResponse['logged'];
        if($logged) {
            $api_token = $loginResponse['api_token'];
            $response = [
                'role' => $loginResponse['role'],
                'api_token' => $api_token
            ];
            return response()->json($response);
        }
        return response()->json([],403);
    }
    function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:3',
            'kim' => 'required'
        ],[
            'email.required' => 'Отсутствует Email',
            'email.email' => 'Некорректный Email',
            'password.required' => 'Отсутствует пароль',
            'password.min' => 'Слишком короткий пароль',
            'kim.required' => 'Отсутствует КИМ'
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors()->toArray(),422);
        }
        $user = new User;
        $users = $user->emailsAndKims();
        $data = $validator->validated();
        $email = $data['email'];
        $emails = [];
        foreach($users as $user_) {
            array_push($emails, $user_['email']);
        }
        if(in_array($email,$emails)) {
            return response()->json(['email' => ['Пользователь с таким Email уже существует']],422);//->header('Access-Control-Allow-Origin','*');
        }
        $password = $data['password'];
        $kim = $data['kim'];
        $user->register($email,$password,$kim);
        return response()->json(true);
    }
    function unregister(Request $request) {
        $user = new User;
        $emails = array_diff(json_decode($request->input('json'),false),['admin@admin.ru']);
        //$emails = json_decode($request->input('json'),false);
        $user->unregister($emails);
        return response()->json(true);
    }
}
