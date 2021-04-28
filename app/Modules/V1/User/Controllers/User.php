<?php
namespace App\Modules\V1\User\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\V1\User\Models\User as UserModel;
use App\Modules\V1\User\Models\ResetToken as Token;
use App\Modules\V1\User\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Validator;


class User extends Controller {
    
    public function list(Request $req)
    {
        if($req->user->rules!=1){
            return $this->response('Tidak ada hak akses',[],403);
        }
        return $this->response("Success",UserModel::select(array("user.*","ud.fullname","ud.level","ud.location","ud.position"))->leftJoin("user_profile as ud","ud.id_user", "=" ,"user.id_user")->get());
    }
    public function update(Request $req,$id){
        if($req->user->rules!=1){
            return $this->response('Tidak ada hak akses',[],403);
        }
        $body =(Object)$req->json()->all();
        $rules= array(
                        'username'=>'required',
                        'fullname'=>'required',
                        'level'=>'required',
                        'location'=>'required',
                        'position'=>'required'

                     );
        
        $validator = Validator::make($req->json()->all(),$rules);
        if($validator->passes()){
            UserDetail::where('id_user',$id)->update(array(
                        "fullname"=> $body->fullname,
                        "level"=> $body->level,
                        "location"=> $body->location,
                        "position"=> $body->position,
                     ));
            return $this->response('Success mengubah data pegawai');
        }
        else{
            return $this->response('Tidak boleh ada data kosong',$validator->errors(),400);

        }
    }
    public function delete(Request $req,$id){
        if($req->user->rules!=1){
            return $this->response('Tidak ada hak akses',[],403);
        }
        UserModel::where('id_user',$id)->delete();
        return $this->response('Success menghapus data pegawai');

    }
    public function register(Request $req){
        $body =(Object)$req->json()->all();
        $rules= array(
                        'username'=>'required',
                        'password'=>'required',
                        'fullname'=>'required'
                     );
         $message = array(
            'username.required'=>'Username tidak boleh kosong',
            'password.required'=>'Password harus di isi',
            'fullname.required'=>'Nama Lengkap tidak boleh kosong',

         );
        
        $validator = Validator::make($req->json()->all(),$rules,$message);

        if($validator->passes()){
             $password =Hash::make($body->password);
             $authkey = Str::random(32);
             $checkuser = UserModel::where('username',$body->username)->first();
            
             if($checkuser){
                 return $this->response('Username sudah terpakai',array(),403);
             }
             else{
                $user = new UserModel();
                $user->username = $body->username;
                $user->password = $password;
                $user->auth_key = $authkey;
                $user->save();

                 if($user->id_user){
                     $detail = new UserDetail();
                     $detail->id_user = $user->id_user;
                     $detail->fullname = $body->fullname;
                     $detail->level    = !empty($body->level)?$body->level:null;
                     $detail->location = !empty($body->location)?$body->location:null;
                     $detail->email    = !empty($body->email)?$body->email:null;
                     $detail->phone    = !empty($body->phone)?$body->phone:null;
                     $detail->position = !empty($body->position)?$body->position:null;
                     $detail->save();
                     $user_ = UserModel::select(array(
                                        'user.id_user',
                                        'user.username',
                                        'user_profile.fullname',
                                        'user_profile.phone',
                                        'user_profile.level',
                                        'user_profile.location',
                                        'user_profile.email',
                                ))
                                ->where('user.id_user',$user->id_user)
                                ->leftJoin('user_profile','user_profile.id_user','=','user.id_user')
                                ->first();
                     return $this->response('Pegawai berhasil ditambahkan',
                                     array(
                                            'id_user' => $user_->id_user,
                                            'username'=>$user_->username,
                                            'email'=>$user_->email,
                                            'fullname' => $user_->fullname,
                                            'phone' => $user_->phone,
                                            'location' => $user_->location,
                                            'level' => $user_->level,
                                            'token' =>$this->jwt($user_)
                                         ));
                 }
                 else{
                    return $this->response('Gagal',
                                     array(),500);
                 }
             }
        }
        else{
            return $this->response('Invalid Parameters',$validator->errors(),400);
        }
     }

     public function me(Request $req)
     {
        $user = UserModel::select(array(
            'user.id_user',
            'user.username',
            'user_profile.email',
            'user_profile.fullname',
            'user_profile.level',
            'user_profile.location',
            'user_profile.phone',
            'user_profile.position',
        ))
        ->where('user.id_user',$req->user->id_user)
        ->leftJoin('user_profile','user_profile.id_user','=','user.id_user')
        ->first();
        return $this->response('Sukses mengambil data Profile',$user);
     }
     public function changePassword(Request $req)
     {
        $body =(Object)$req->json()->all();
        $rules= array(
            'passwordnow'=>'required',
            'passwordnew'=>'required',
            'passwordconfirm'=>'required'
         );
        $message = array(
        'passwordnow.required'=>'Password Saat ini tidak boleh kosong',
        'passwordnew.required'=>'Password Baru harus di isi',
        'passwordconfirm.required'=>'Ulangi password baru',

        );

        $validator = Validator::make($req->json()->all(),$rules,$message);
        if($validator->passes()){
            $user = UserModel::select(array(
                'user.password'
            ))
            ->where('user.id_user',$req->user->id_user)->first();
            if(Hash::check($body->passwordnow,$user->password)){
                if($body->passwordnew==$body->passwordconfirm){
                    $password =Hash::make($body->passwordnew);
                    $ubah = UserModel::where("id_user",$req->user->id_user)->update(array('password'=>$password));
                    $this->response('Password berhasil di ubah',[]);
                }
                else{
                    return $this->response('Password baru tidak cocok',$validator->errors(),400);
                }
            }
            else{
                return $this->response('Password lama salah',$validator->errors(),400);
            }

        }
        else{
            return $this->response('Invalid Parameters',$validator->errors(),400);
        }
        return $this->response('Sukses mengambil data Profile',$user);
     }

}