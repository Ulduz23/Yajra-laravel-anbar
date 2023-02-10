<?php

namespace App\Exports;

use App\Models\products;
use App\Models\brands;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;


class productsexport implements FromCollection,WithHeadings
{
    public function headings():array{
       return[
        "BREND",
        "MEHSUL",
        "ALISH",
        "SATISH",
        "MIQDAR",
        "CREATED_AT"];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return products::join('brands','brands.id','=','products.brand_id')
        ->select('brands.brand','mehsul','alish','satish','miqdar','products.created_at')
        ->where('products.user_id','=',Auth::id())
        ->get();
    }

}
