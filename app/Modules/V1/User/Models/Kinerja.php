<?php
namespace App\Modules\V1\User\Models;
use Illuminate\Database\Eloquent\Model;

class Kinerja extends Model {
    protected $table = 'kinerja';
    protected $primaryKey = 'id_kinerja';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'description',
        'metrix',
        'volume',
        'created_at',
        'updated_at'
    ];

}