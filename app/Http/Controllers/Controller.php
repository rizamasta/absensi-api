<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Firebase\JWT\JWT;

class Controller extends BaseController
{
    public function response($message='Success', $data = array(),$statusCode = 200){
        $res_data =array(
                        'status' => $statusCode,
                        'message' => $message,
                        'data'=> $data
                    );
        return response()->json($res_data,$statusCode);
    }

    protected function jwt($user) {
        $payload = [
            'id_user' => $user->id_user,
            'email' => $user->email,
            'username' => $user->username, 
            'expired' => time() + 60*60
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
}
