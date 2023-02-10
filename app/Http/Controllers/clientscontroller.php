<?php

namespace App\Http\Controllers;

use App\Exports\clientsexport;
use Illuminate\Http\Request;

use App\Models\clients;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;

class clientscontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(clients::select('*')
            ->where('user_id','=',Auth::id()))
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
        return view('clients');
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
             
            $book = clients::find($bookId);

            if($request->hasFile('image')){
                $path = $request->file('image')->store('public/images');
                $book->image = $path;
            }   
         }else{
                $path = $request->file('image')->store('public/images');
               $book = new clients();
               $book->image = $path;
         }
         
        $book->client = $request->client;
        $book->soyad = $request->soyad;
        $book->telefon = $request->telefon;
        $book->email = $request->email;
        $book->sirket = $request->sirket;
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
        $book  = clients::where($where)->first();
     
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
        $book = clients::where('id',$request->id)->delete();
     
        return Response()->json($book);
    }
    
    public function export(){
        return Excel::download(new clientsexport,'clients.xlsx');
    }
}