<?php

namespace App\Exports;

use App\Models\orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class ordersexport implements FromCollection,WithHeadings
{
    public function headings():array{
       return[
        "MUSTERI", 
        " ",
        "MEHSUL",
        "BREND",
        "ALISH",
        "SATISH",
        "STOK",
        "SIFARISHIN MIQDARI",
        "CREATED_AT"];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return orders::join('clients','clients.id','=','orders.client_id')
        ->join('products','products.id','=','orders.product_id')
        ->join('brands','brands.id','=','products.brand_id')
        ->select('clients.client','clients.soyad','products.mehsul','brands.brand','products.alish','products.satish','products.miqdar','orders.sifarish','orders.created_at',)
        ->where('orders.user_id','=',Auth::id())
        ->get();
    }

}
