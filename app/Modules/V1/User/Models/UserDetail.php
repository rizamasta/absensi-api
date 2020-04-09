<?php
namespace App\Modules\V1\User\Models;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model {
    protected $table = 'user_profile';
    protected $primaryKey = 'profile_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            "profile_id",
            "fullname",
            "phone",
            "email",
            "level",
            "location",
            "created_at",
            "updated_at"
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}