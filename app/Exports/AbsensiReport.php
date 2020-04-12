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

        for ($day = 6; $day >= 2; $day--) {
            $start = date('Y-m-d', strtotime('-'.$day.' days'));
            $data =  new AbsenPerDay($start);
            if($data){
                $sheets[] =$data;
            }
        }
        return $sheets;
    }
}