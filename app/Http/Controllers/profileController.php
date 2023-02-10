<?php

namespace App\Http\Controllers;

use App\Http\Requests\userRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class profileController extends Controller
{
    public function index(){
        return view('profile');
    }

     
    public function profile(userRequest $post){
        
        $con = User::find(Auth::id());

        if(Hash::check($post->password, $con->password)){

                    $yoxla = User::where('id','!=',$post->id)->count();
        
                        if($yoxla==0){                   
                            $post->validate([
                            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:4096',
                            ]);
                        }
                     
                            if($post->file('image')){
                                
                                $file = time().'.'.$post->image->extension();
                                $post->image->storeAs('public/uploads/fotolar/',$file);
                                $con->image = 'storage/uploads/fotolar/'.$file;
                            }
                            else{
                                $con->image = $con->image;
                            }

                            if(empty($post->newpass))
                            {$con->password = Hash::make($post->password);}
                            else{$con->password = Hash::make($post->newpass);}

                    $con->name = $post->name;
                    $con->surname = $post->surname;
                    $con->email = $post->email;
                    
                    $con->save();
                    
                    return redirect()->route('myprofile')->with('mesaj','Profiliniz yeniləndi');
        }
        return redirect()->route('myprofile')->with('mesaj','Cari Şifrə yanlışdır');
    }


}
