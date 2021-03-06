<?php
namespace App\Modules\V1\Absensi\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\V1\User\Models\User;
use App\Modules\V1\Absensi\Models\Absensi as AbsensiModel;
use App\Exports\AbsensiReport;
use  Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;


class Absensi extends Controller {
    
    public function list(Request $req)
    {
        $data = AbsensiModel::select("*")
                            ->selectRaw("TIMEDIFF(punch_out,punch_in) as duration")
                            ->where('id_user',$req->user->id_user)->where('status',1)->limit(30)->orderby('punch_in','desc')->get();
        return $this->response('History absensi',$data);
    }

    public function checkpoint(Request $req)
    {
        $data = AbsensiModel::where('id_user',$req->user->id_user)->where('status',0)->first();
        if($data){
            $res = array('create'=>false);
        }
        else{
            $res = array('create'=>true);
        }
        $current =AbsensiModel::where('id_user',$req->user->id_user)
                                ->where('status',1)
                                ->where(DB::raw('DATE_FORMAT(punch_in,"%d-%m-%Y")'),date('d-m-Y'))->first();
        if($current){
        return $this->response('Anda sudah Absen Masuk dan Pulang hari ini. Terima Kasih',[],403);

        }
        return $this->response('Checkpoint',$res);
    }
    public function punchin(Request $req)
    {
        $current =AbsensiModel::where('id_user',$req->user->id_user)->where(DB::raw('DATE_FORMAT(punch_in,"%d-%m-%Y")'),date('d-m-Y'))->first();
        if($current){
            return $this->response('Maaf, Absen masuk ditolak. Karena Anda sudah melakukan Absen. Silahkan coba lagi besok',[],403);
        }
        else{
            $data = new AbsensiModel();
            $data->id_user = $req->user->id_user;
            return $this->response('Absen masuk sukses',$data->save());
        }
    }
    public function punchout(Request $req)
    {
        $current = AbsensiModel::where('id_user',$req->user->id_user)->where('status',0);
        if($current->first()){
            return $this->response('Absen keluar sukses',$current->update(array('status'=>1)));
        }
        else{
            return $this->response('Absen gagal',[],400);
        }
    }
    public function export(Request $req)
    {
        if($req->user->rules!=1){
            return $this->response('Tidak ada hak akses',[],403);
        }
        return Excel::download(new AbsensiReport, date('YmdTHis').'.xlsx');
    }
}