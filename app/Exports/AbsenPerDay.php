<?php 
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Modules\V1\Absensi\Models\Absensi as AbsensiModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;


class AbsenPerDay implements FromQuery, WithTitle,WithHeadings
{
    private $date;
    private $days=["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA',
            'GOLONGAN',
            'SATUAN',
            'JABATAN',
            'TANGGAL',
            'MASUK',
            'PULANG',
            'DURASI'
        ];
    }
    /**
     * @return Builder
     */
    public function query()
    {   
         DB::statement(DB::raw('set @row=0'));
        return AbsensiModel::select(array(DB::raw('@row := @row + 1 AS SrNo'),"fullname","level","location","position"))
                            ->selectRaw("DATE_FORMAT(punch_in,'%d-%m-%Y') as tanggal")
                            ->selectRaw("DATE_FORMAT(punch_in,'%H:%i') as masuk")
                            ->selectRaw("DATE_FORMAT(punch_out,'%H:%i') as pulang")
                            ->selectRaw("TIMEDIFF(punch_out,punch_in) as duration")
                            ->leftJoin("user_profile","user_profile.id_user","absensi.id_user")
                            ->where(DB::raw('DATE_FORMAT(punch_in,"%Y-%m-%d")'),$this->date)
                            ->where('absensi.id_user',"!=",1)
                            // ->where('status',1)
                            ->orderBy('srNo','asc');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->days[date("w",\strtotime($this->date))];
    }
}