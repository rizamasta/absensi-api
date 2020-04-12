<?php
namespace App\Modules\V1\File\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Exports\AbsensiReport;
use  Maatwebsite\Excel\Facades\Excel;
class Files {
    public function read($name="empty"){
        try {
            //code...
            Excel::store(new AbsensiReport, $name.'.xlsx');
            return  Storage::download($name.'.xlsx');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return "File not found";
        }
    }
}