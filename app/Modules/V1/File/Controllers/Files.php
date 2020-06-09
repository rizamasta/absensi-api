<?php
namespace App\Modules\V1\File\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Exports\AbsensiReport;
use App\Exports\KinerjaReport;
use  Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
class Files {
    public function read($name="empty",$id="1",Request $req){
        try {
            //code...
            if($name=="absensi"){
                $date = date('Y-m-d');
                $n = "Absensi_Report_".$date.'.xlsx';
                Excel::store(new AbsensiReport, $n);
                return  Storage::download($n);
            }
            else if($name=="kinerja"){
                $date = !empty($req->date)?date('Y-m-d',\strtotime($req->date)):date('Y-m-d');
                $n = "Kinerja-harian_".$date.'.xlsx';
                Excel::store(new KinerjaReport($id,$date), $n);
                return  Storage::download($n);
            }
           
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return "File not found";
        }
    }
}