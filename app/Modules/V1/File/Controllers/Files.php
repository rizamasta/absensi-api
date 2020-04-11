<?php
namespace App\Modules\V1\File\Controllers;
use Illuminate\Support\Facades\Storage;
class Files {
    public function read($name="empty"){
        try {
            //code...
            return  Storage::download($name.'.xlsx');
        } catch (\Throwable $th) {
            //throw $th;
            return "File not found";
        }
    }
}