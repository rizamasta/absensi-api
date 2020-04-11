<?php 
namespace App\Exports;
use App\Modules\V1\Absensi\Models\Absensi as AbsensiModel;
use Maatwebsite\Excel\Concerns\FromCollection;
class Report implements FromCollection
{
    public function collection()
    {
        return AbsensiModel::all();
    }
}