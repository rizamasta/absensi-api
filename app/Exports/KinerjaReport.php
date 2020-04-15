<?php 
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Modules\V1\User\Models\UserDetail;
use App\Modules\V1\User\Models\Kinerja;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KinerjaReport implements FromView,ShouldAutoSize
{
    use Exportable;
    private $id_user;
    private $date;
    public function __construct($id,$date){
        $this->date=$date;
        $this->id_user=$id;
    }
    public function view(): View
    {
        $user = UserDetail::where('id_user',$this->id_user)->first();
        $kinerja = Kinerja::where('id_user',$this->id_user)->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),$this->date)->get();
        return view('kinerja', [
            'user' => $user,
            'kinerja' => $kinerja,
            'tanggal' => date('d-m-Y',\strtotime($this->date))
        ]);
    }
}