<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Kim;
use DB;

class AdminController extends Controller
{

    function downloadKimFile(Request $request) {
        $request = $request->all();
        $kim = array_key_exists('kim',$request)?$request['kim']:null;
        $file = array_key_exists('file',$request)?$request['file']:null;
        if($kim!==null&&$file!==null&&
            Storage::exists("kims/$kim")&&
            Storage::exists("kims/$kim/$file")) {
            return Storage::download("kims/$kim/$file");
        }
        return response()->json([],404);
    }

    function getKimData(Request $request) {
        $request = $request->all();
        $params = array_keys($request);
        if(!in_array('kim',$params)) {
            return response()->json([],404);
        }
        $kimName = $request['kim'];
        if(in_array('task',$params)) {
            $task = $request['task'];
            if(Storage::exists("/kims/$kimName/$task.png")) {
                return response()->file("../storage/app/kims/$kimName/$task.png");
            }
            return response()->json([],404);
        }
        else {
            if(!Storage::exists("kims/$kimName")) {
                return response()->json([],404);
            }
            return Storage::get("kims/$kimName/data.json");
        }
    }

    function getKims() {
        $kim = new Kim;
        $kims = $kim->allKims();
        $response = [];
        foreach($kims as $kim) {
            array_push($response, ['kim' => $kim]);
        }
        return response()->json($response);
    }

    function delKims(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'json' => 'json',
            ],
            [
                'json.json' => 'Невалидная строка JSON',
            ]
        );
        if($validator->fails()) {
            return response()->json($validator->errors()->toArray(),422);
        }
        $data = $validator->validated();
        $kims = json_decode($data['json'],false);
        foreach($kims as $dir) {
            Storage::deleteDirectory("kims/$dir");
        }
        DB::table('kims')
        ->whereIn('kim',$kims)
        ->delete();
        return response()->json(true);
    }

    function addKim(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'kim' => 'required',
                'file' => 'required|file'
            ],
            [
                'kim.required' => 'Отсутствует ID КИМ',
                'file.file' => 'Ошибка загрузки файла',
                'file.required' => 'Отсутствует файл'
            ]
        );
        if($validator->fails()) {
            return response()->json($validator->errors()->toArray(),422);
        }
        $data = $validator->validated();
        $name = $data['kim'];
        $kim = new Kim;
        if(in_array($name,$kim->allKims())) {
            return response()->json(['kim' => ['КИМ с таким ID уже существует']],422);
        }
        $request->file('file')->storeAs("kims/$name","$name.zip");
        $zipErrors = $kim->unpackZip(
            "../storage/app/kims/$name/$name.zip",
            "../storage/app/kims/$name"
        );
        $dirs = Storage::directories("kims/$name");
        if(count($zipErrors)) {
            return response()->json($zipErrors,422);
        }
        foreach($dirs as $dir) {
            Storage::deleteDirectory("kims/$name/$dir");
        }
        $kim->makeDataFile($name);
        DB::table('kims')
        ->updateOrInsert(
            ['kim' => $name]
        );
        return response()->json(true);
    }

}
