<?php
namespace App\Modules\V1\User\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\V1\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;


class Auth extends Controller {

    public function login(Request $req){
        $body =(Object)$req->json()->all();

        $rules = array(
                        'username'=>'required',
                        'password'=>'required'
                    );
        $message = array(
                        'username.required'=>'Username tidak boleh kosong',
                        'password.required'=>'Password Harus diisi'
                    );
        $validator = Validator::make($req->json()->all(),$rules,$message);
        if($validator->passes()){
            $user = User::select(array( 'user.*',
                                        'user_profile.fullname',
                                        'user_profile.phone',
                                        'user_profile.email',
                                        'user_profile.level',
                                        'user_profile.location',
                                        'user_profile.position',
                                ))
                    ->where('username',$body->username)
                    ->leftJoin('user_profile','user_profile.id_user','=','user.id_user')
                    ->first();
            if($user){
                if(Hash::check($body->password,$user->password)){
                    $data = array(
                                    'id_user' => $user->id_user,
                                    'username' => $user->username,
                                    'email' =>$user->email,
                                    'name' => $user->fullname,
                                    'location' =>$user->location,
                                    'level' =>$user->level,
                                    'position' =>$user->position,
                                    'phone' => $user->phone,
                                    'rules' => $user->rules,
                                    'token' =>$this->jwt($user)
                    );
                    return $this->response('Welcome back, '.$user->fullname,$data);
                }
                else{
                    return $this->response('Username atau Password salah',[],400);
                }
            }
            else{
                return $this->response('User tidak terdaftar',[],400);
            }
            
        }
        else{
            return $this->response('Invalid Parameters',$validator->errors(),400);
        }
    }
}