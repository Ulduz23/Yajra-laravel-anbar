<?php

namespace App\Exports;

use App\Models\clients;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class clientsexport implements FromCollection,WithHeadings
{
    public function headings():array{
       return[
        "AD",
        "Soyad",
        "TELEFON",
        "EMAIL",
        "SIRKET",
        "CREATED_AT"];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return clients::select('client','soyad','telefon','email','sirket','created_at')->get();
    }

}
