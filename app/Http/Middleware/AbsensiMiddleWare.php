<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Modules\V1\User\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AbsensiMiddleWare  extends Controller
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('x-access-token');
        if(!$request->header('x-access-token')) {
            return $this->response('Tidak ada token',[],401);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return $this->response('Token sudah berakhir',[],401);
        } catch(Exception $e) {
            return $this->response('Format token salah',[],400);
        }
        $user = User::select(array(
                            'user.id_user',
                            'user.username',
                            'user.rules',
                            'user_profile.email',
                            'user_profile.fullname',
                            'user_profile.level',
                            'user_profile.location',
                            'user_profile.phone',
                            'user_profile.position',
                    ))
                    ->where('user.id_user',$credentials->id_user)
                    ->leftJoin('user_profile','user_profile.id_user','=','user.id_user')
                    ->first();
        if($user){
            $request->user = $user;
            return $next($request);
        }
        else{
            return $this->response('Tidak bisa menampilkan user',[],401);
        }
        
    }
}

