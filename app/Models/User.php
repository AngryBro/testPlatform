<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Hash;
use Str;
use Carbon\Carbon;

class User extends Model
{

    function getIdByToken($api_token) {
        $response = DB::table('users')
        ->select('id')
        ->where('api_token',$api_token)
        ->first();
        if($response!==null) {
            return $response->id;
        }
    }

    function hasActiveTest($id) {
        $response = DB::table('answers')
        ->select('end')
        ->orderBy('id','desc')
        ->first();
        if($response === null) {
            return false;
        }
        $now = Carbon::now();
        $end = Carbon::parse($response->end);
        return $end->gt($now);
    }

    function startExam($id) {
        DB::table('answers')
        ->insert([
            'user_id' => $id,
            'answers' => json_encode([]),
            'start' => Carbon::now()->toDateTimeString(),
            'end' => Carbon::now()->addHours(3)->addMinutes(55)
        ]);
    } 

    function endExam($id) {
        $activeTestId = $this->activeTestId($id);
        DB::table('answers')
        ->where('id',$activeTestId)
        ->update([
            'end' => Carbon::now()->toDateTimeString()
        ]);
    }

    function activeTestId($id) {
        $activeTestId = DB::table('answers')
        ->select('id')
        ->where('user_id',$id)
        ->orderBy('id','desc')
        ->first()->id;
        return $activeTestId;
    }

    function getSavedAnswers($id) {
        $response = DB::table('answers')
        ->select('answers')
        ->where('user_id',$id)
        ->orderBy('id','desc')
        ->first();
        if($response===null) {
            return [];
        }
        return json_decode($response->answers,true);
    }

    function saveAnswers($answers,$id) {
        $activeTestId = $this->activeTestId($id);
        DB::table('answers')
        ->where('id',$activeTestId)
        ->update(['answers' => $answers]);
    }

    function kimName($api_token) {
        $response = DB::table('users')
        ->select('kim')
        ->where('api_token',$api_token)
        ->first();
        if($response===null) {
            return null;
        }
        return $response->kim;
    }

    function emailsAndKims() {
        $response = DB::table('users')
        ->select('email','kim')
        ->get();
        $emailsAndKims = [];
        foreach($response as $user) {
            array_push($emailsAndKims,[
                'email' => $user->email,
                'kim' => $user->kim
            ]);
        }
        return $emailsAndKims;
    }

    function unregister($emails) {
        DB::table('users')
        ->whereIn('email',$emails)
        ->delete();
    }

    function role($api_token) {
        $role = DB::table('users')
        ->select('role')
        ->where('api_token',$api_token)
        ->first()
        ->role;
        return $role;
    }
    function login($email,$password) {
        $response = DB::table('users')
        ->select('password','api_token','role')
        ->where('email',$email)
        ->first();
        if($response===null) {
            return [
                'logged' => false
            ];
        }
        $correctPassHash = $response->password;
        return [
            'logged' => password_verify($password,$correctPassHash),
            'role' => $response->role,
            'api_token' => $response->api_token
        ];
    }

    function register($email,$password,$kim) {
        DB::table('users')->updateOrinsert(
            ['email' => $email],
            [
                'password' => Hash::make($password),
                'role' => 'user',
                'kim' => $kim,
                'api_token' => Str::random(80)
            ]
        );
    }
}
