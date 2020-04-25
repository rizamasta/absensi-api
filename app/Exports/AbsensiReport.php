<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\AbsenPerDay;

class AbsensiReport implements WithMultipleSheets
{
    use Exportable;
    
    
    public function __construct(){

    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $h = date('Y-m-d',\strtotime('-5 days'));
        for ($day = 0; $day <= 5; $day++) {
            $start = date('Y-m-d', strtotime($h.' +'.$day.' days'));
            $da = date("w",\strtotime($start));
            if($da!=0 && $da!=6){
                $data =  new AbsenPerDay($start);
                $sheets[] =$data;
            }
        }
        return $sheets;
    }
}