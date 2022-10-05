<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use DB;

class Kim extends Model
{

    function unpackZip($zipPath,$toPath) {
        $zipErrors = [];
        $zip = new ZipArchive;
        $name = explode('/',$toPath);
        $name = $name[count($name)-1];
		$opened = $zip->open($zipPath);
        if($opened) {
            $extracted = $zip->extractTo($toPath);
            if($extracted) {
                $zip->close();
                Storage::delete("kims/$name/$name.zip");
            }
            else {
                array_push($zipErrors,'Архив не удалось распаковать');
            }
        }
        else {
            array_push($zipErrors,'Архив не удалось открыть');
        }
        return $zipErrors;
    }

    function makeDataFile($kim) {
        $fileString = Storage::get("kims/$kim/data.txt");
        $lines = explode("\n",$fileString);
        $linesWithPoints = [];
        $temp = [];
        foreach($lines as $line) {
            $line = trim($line);
            $posPoints = strpos($line,':');
            if($posPoints!==false) {
                if(!empty($temp)) {
                    $table = implode("\n",$temp);
                    array_push($linesWithPoints,$table);
                    $temp = [];
                }
                if($line[-1]==':') {
                    array_push($temp,$line);
                }
                else {
                    array_push($linesWithPoints,$line);
                }
            }
            else {
                array_push($temp,$line);
            }
        }
        if(!empty($temp)) {
            $table = implode("\n",$temp);
            array_push($linesWithPoints,$table);
        }
        $kimData = [];
        foreach($linesWithPoints as $line) {
            $splited = explode(':',$line);
            $number = $splited[0];
            $answerAndFiles = count($splited)==1?'noAnswer':$splited[1];
            $splited = explode(',',$answerAndFiles);
            $answer = trim($splited[0]);
            $files = [];
            for($i = 1; $i<count($splited); $i++) {
                array_push($files,$splited[$i]);
            }
            $kimData[$number] = [
                'answer' => $answer,
                'files' => $files
            ];
        }
        Storage::put("kims/$kim/data.json",json_encode($kimData));
    }

    function allKims() {
        $kims = [];
        $response = DB::table('kims')
        ->select('kim')
        ->get();
        foreach($response as $note) {
            array_push($kims, $note->kim);
        }
        return $kims;
    }

}
