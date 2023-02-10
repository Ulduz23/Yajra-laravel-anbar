<?php

namespace App\Imports;

use App\Models\brands;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingsRow;

class brandsimport implements ToModel, WithHeadingsRow
{
    
    public function headingsRow(): int
    {
        return 2;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new brands([
            'Brand'=>$row['brand'],
            'Created_at'=>$row['created_at'],
        ]);
    }
}
