<?php

namespace App\Exports;

use App\Models\brands;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class brandsexport implements FromCollection,WithHeadings
{
    public function headings():array{
       return[
        "Brands",
        "created_at"];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return brands::select('brand','created_at')->get();
    }

}
