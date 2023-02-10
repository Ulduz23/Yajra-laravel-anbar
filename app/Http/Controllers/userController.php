<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\userRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class userController extends Controller
{
    public function register(userRequest $post){

    $con = new User();

    $yoxla = User::where('email','=', $post->email)->orwhere('password','=',$post->password)
    ->count();

    if($yoxla==0)
    {
        $con->blok = 0;
        $con->image = 'https://t3.ftcdn.net/jpg/04/34/72/82/360_F_434728286_OWQQvAFoXZLdGHlObozsolNeuSxhpr84.jpg';
        $con->name = $post->name;
        $con->surname = $post->surname;
        $con->telefon = $post->telefon;

        $con->email = $post->email;
        $con->password = Hash::make($post->password);

        $con->save();
        return redirect()->route('daxilol');

    }
    return back()->with('mesaj','Bu istifadeci artiq movcuddur!');

    }

    public function login(Request $post){

        $this->validate($post,[
            'email'=>'email|required',
            'password'=>'required'

        ]);

        if(!Auth::attempt(['email'=>$post->email,'password'=>$post->password, 'blok'=>0])){
        return redirect()->back()->with('mesaj','Daxil etdiyiniz login ve ya parol yanlishdir');
        }

    return redirect()->route('orders');
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('daxilol');
    }

    public function forgot(){
        return view('forgot');
    }
    
    public function reset(Request $post, $token = null){
        return view('reset');
    }

    public function email_forgot(){
        return view('email_forgot');
    }

    public function sendlink(Request $post){
        $post->validate([
            'email'=>'required|email|exists:users,email'
        ]);
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email'=> $post->email,
            'token'=>$token,
            'created_at'=> Carbon::now(),
        ]);

            $to = $post->email;

            echo"TO: ".$to."<br>";
            $subject = "RESET PASSWORD";

            $m ="We have sent the link to your email. You can reset your password by clincking the link this <a href='ulduz.ml/reset'>RESET PASSWORD</a>\n\n";
            $headers = "From: ulduz@ulduz.ml\r\n" . "X-Mailer: php";
            $headers.= 'MIME-Version: 1.0' . "\r\n";
            $headers.= 'Content-type: text/html; charset=utf-8 \r\n';


            if (mail($to, $subject, $m, $headers)){
                return back()->with('mesaj','We have e-mailed your pasword reset link');
            }
            else {
                return back()->with('mesaj','Message delivery failed...');
            }
    }

    public function resetparol(Request $post){

        $con = User::find(Auth::id());
       if($post->password =  $post->conpass)
       {
        $con->password = Hash::make($post->password);            
        $con->save(); 
    
        return redirect()->route('resetform')->with('mesaj','Parol yenilendi!');

       }
    return redirect()->route('resetform')->with('mesaj','Parollar eyni olmalidir!');


    }

    
}
