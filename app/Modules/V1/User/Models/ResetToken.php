<?php
namespace App\Modules\V1\User\Models;
use Illuminate\Database\Eloquent\Model;

class ResetToken extends Model {
    protected $table = 'token';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
        'created_at',
        'type',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}