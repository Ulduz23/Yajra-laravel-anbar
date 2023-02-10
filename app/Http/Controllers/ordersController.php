<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\products;
use App\Models\brands;
use App\Models\clients;
use App\Models\orders;
use App\Exports\ordersexport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class ordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(
            orders::join('clients','clients.id','=','orders.client_id')
            ->join('products','products.id','=','orders.product_id')
            ->join('brands','brands.id','=','products.brand_id')
            ->select('brands.brand','clients.client','clients.soyad','products.miqdar','products.mehsul','products.alish','products.satish','orders.created_at','orders.id','orders.sifarish','orders.tesdiq')
            ->where('orders.user_id','=',Auth::id())
            ->orderBy('id','desc')
            ->get())
            ->addColumn('created_at',function($row){
                return date('Y-m-d H:i:s',strtotime($row->created_at));
            })
            ->addColumn('action',function($row){

                if($row->tesdiq==0)
                {
                    return '
                    <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-warning edit btn-sm" id="edit">
                    Edit</a>
                    <a href="javascript:void(0);" id="delete" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'"  class="delete btn btn-danger btn-sm">
                    Delete</a>
                    <a href="javascript:void(0);" id="tesdiq" data-toggle="tooltip" data-original-title="Tesdiq" data-id="'.$row->id.'"  class="tesdiq btn btn-success btn-sm">
                    Tesdiq</a>';
                }
                else
                {
                    return 
                    '<a href="javascript:void(0);" id="legv" data-toggle="tooltip" data-original-title="legv" data-id="'.$row->id.'"  class="legv btn btn-danger btn-sm">
                    Legv</a>';}

            })
            ->rawColumns(['action','image'])
            ->addIndexColumn()
            ->make(true);
        }

        $bdata = brands::where('brands.user_id','=',Auth::id())
        ->orderBy('id','desc')->get();
        $cdata = clients::where('clients.user_id','=',Auth::id())
        ->orderBy('id','desc')->get();

        $pdata = products::join('brands','brands.id','=','products.brand_id')
        ->select('brands.brand','products.id','products.miqdar','products.mehsul','products.alish','products.satish')
        ->where('products.user_id','=',Auth::id())
        ->orderBy('products.id','desc')->get();

        return view('orders',[
            'bdata'=>$bdata,
            'cdata'=>$cdata,
            'pdata'=>$pdata
        ]);
    }
     
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  

        $ordersid = $request->id;

        if($ordersid){
             
            $con = orders::find($ordersid);
      
         }
         else{$con = new orders();}

        $con->tesdiq = 0; 
        $con->client_id = $request->client_id;
        $con->product_id = $request->product_id;
        $con->sifarish = $request->sifarish;
        $con->user_id = Auth::id();

        $con->save();
     
        return Response()->json($con);


    }
     
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $book  = orders::where($where)->first();
     
        return Response()->json($book);
    }
     
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $book = orders::where('id',$request->id)->delete();
     
        return Response()->json($book);
    }

    public function tesdiq(Request $request){
        
        $orders = orders::find($request->id);      
        $omiq = $orders->sifarish;  
        $products = products::find($orders->product_id);   
        $pmiq = $products->miqdar;
        if($omiq<$pmiq)
        {
            $miq=$pmiq-$omiq;
            $products->miqdar=$miq;
            $products->save();

            $orders->tesdiq=1;
            $orders->save();

        }
        else
        { $orders= 'Bazada kifayet qeder mehsul yoxdur!'; }

       return Response()->json($orders);

        }
        

    public function legv(Request $request){
        
        $orders = orders::find($request->id);      
        $omiq = $orders->sifarish;   
        $products = products::find($orders->product_id);       
        $pmiq = $products->miqdar;

        $miq=$pmiq+$omiq;
        $products->miqdar=$miq;
        $products->save();

        $orders->tesdiq=0;
        $orders->save();
        
        $orders= 'Sifarishiniz legv edildi';

    return Response()->json($orders);
    
    }

    
    public function export(){
        return Excel::download(new ordersexport,'orders.xlsx');
    }
}    