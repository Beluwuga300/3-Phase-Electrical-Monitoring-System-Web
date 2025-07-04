<?php
// untuk export data energi listrik ke Excel

namespace App\Exports;

use App\Models\EnergiListrik;
use Maatwebsite\Excel\Concerns\FromCollection;

class EnergiListrikExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EnergiListrik::all();
    }
}
