<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\productsexport;

use App\Models\products;
use App\Models\brands;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;


class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(products::join('brands','brands.id','=','products.brand_id')
            ->select('products.mehsul','brands.brand','products.alish','products.satish','products.miqdar','products.image','products.brand_id','products.created_at','products.id')
            ->where('products.user_id','=',Auth::id())
            ->orderBy('products.id','desc')
            ->get())
            ->addColumn('action',function($row){
                return '
                <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-warning edit btn-sm" id="edit">
                Edit</a>
                <a href="javascript:void(0);" id="delete" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'"  class="delete btn btn-danger btn-sm">
                Delete</a>';
            })
            ->addColumn('image', 'image')
            ->rawColumns(['action','image'])
            ->addColumn('created_at',function($row){
                return date('Y-m-d H:i:s',strtotime($row->created_at));
            })
            ->addIndexColumn()
            ->make(true);
        }

        $bdata = brands::where('user_id','=',Auth::id())
        ->orderBy('id','desc')->get();

        return view('products',[
            'bdata'=>$bdata
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

        $bookId = $request->id;

        if($bookId){
             
            $book = products::find($bookId);
    
            if($request->hasFile('image')){
                $path = $request->file('image')->store('public/images');
                $book->image = $path;
            }   
         }else{
                $path = $request->file('image')->store('public/images');
               $book = new products();
               $book->image = $path;
         }
         
        $book->brand_id = $request->brand_id;
        $book->mehsul = $request->mehsul;
        $book->alish = $request->alish;
        $book->satish = $request->satish;
        $book->miqdar = $request->miqdar;
        $book->user_id = Auth::id();

        $book->save();
     
        return Response()->json($book);


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
        $book  = products::where($where)->first();
     
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
        $book = products::where('id',$request->id)->delete();
     
        return Response()->json($book);
    }

     
    public function export(){
        return Excel::download(new productsexport,'products.xlsx');
    }
}