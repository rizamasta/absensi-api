<?php
namespace App\Modules\V1\User\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\V1\User\Models\Kinerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;


class Kinerjaharian extends Controller {
    public function list(Request $req){
        $date = !empty($req->date)?$req->date:date('Y-m-d');
        $can_update = $date==date('Y-m-d')?"1":"0";
        $data = Kinerja::select("*")->selectRaw($can_update." as can_edit")
                        ->where("id_user",$req->user->id_user)
                        ->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),$date)
                        ->get();
         return $this->response('Kinerja harian',$data);
    }
    public function report(Request $req){
        $date = !empty($req->date)?$req->date:date('Y-m-d');
        return Excel::download(new KinerjaReport($req->user->id_user,$date), 'Kinerja-harian_'.$date.'.xlsx');
    }
    public function create(Request $req){
        $body = (Object)$req->json()->all();
        $rules = array(
            'description'=>'required',
            'metrix'=>'required',
            'volume'=>'required',
            'output'=>'required',
        );
        // dd($body);
        $message = array(
                    'description.required'=>'required',
                    'metrix.required'=>'required',
                    'volume.required'=>'required',
                    'output.required'=>'required',
                );
        $validator = Validator::make($req->json()->all(),$rules,$message);
        if($validator->passes()){
            $data = array(
                            "id_user" => $req->user->id_user,
                            "description" => $body->description,
                            'metrix'=> $body->metrix,
                            'volume'=>$body->volume,
                            'output'=> $body->output
            );
            if(Kinerja::insert($data)){
                return $this->response('Berhasil menambah data');
            }
            else{
                return $this->response('Error',$validator->errors(),400);
            }
        }
        else{
            return $this->response('Invalid Parameters',$validator->errors(),400);
        }
    }

    public function update(Request $req,$id){
        $body = (Object)$req->json()->all();
        $rules = array(
            'description'=>'required',
            'metrix'=>'required',
            'volume'=>'required',
            'output'=>'required',
        );
        // dd($body);
        $message = array(
                    'description.required'=>'required',
                    'metrix.required'=>'required',
                    'volume.required'=>'required',
                    'output.required'=>'required',
                );
        $validator = Validator::make($req->json()->all(),$rules,$message);
        if($validator->passes()){
            $data = array(
                            "description" => $body->description,
                            'metrix'=> $body->metrix,
                            'volume'=>$body->volume,
                            'output'=> $body->output
            );
            $upd = Kinerja::where('id_kinerja',$id);
            if($upd->update($data)){
                return $this->response('Berhasil mengubah data');
            }
            else{
                return $this->response('Error',$validator->errors(),400);
            }
        }
        else{
            return $this->response('Invalid Parameters',$validator->errors(),400);
        }
    }
    public function delete(Request $req,$id){
        if(Kinerja::where('id_kinerja',$id)->delete()){
            return $this->response('Berhasil menghapus data');
        }
        else{
            return $this->response('Gagal menghapus data',[],400);
        }
    }
}