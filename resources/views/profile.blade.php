@extends('layouts.app')

@section('profile')

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

 
<div class="container">

@if($errors->any())
  @foreach($errors->all() as $sehv)
   <div class="alert alert-danger"> {{$sehv}}<br></div>
  @endforeach
@endif

@if(session('mesaj'))
  <div class="alert alert-success">{{session('mesaj')}}</div>
@endif
 
<br>



<form method="post" action="{{route('profile')}}" enctype="multipart/form-data">
    @csrf
    Foto:<br>
    <div>
        <img src="{{url(Auth::user()->image)}}" style="weight:70px; height:60px"><br>
        <input type="file" class="form-control" name="image"><br>
    </div>
    ADINIZ:<br>
    <div>
        <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}"><br>
    </div>
    SOYADINIZ:<br>
    <div>
        <input type="text" class="form-control" name="surname" value="{{Auth::user()->surname}}"><br>
    </div>
    TELEFON:<br>
    <div>
        <input type="text" class="form-control" name="telefon" value="{{Auth::user()->telefon}}"><br>
    </div>
    EMAIL:<br>
    <div>
        <input type="text" class="form-control" name="email" value="{{Auth::user()->email}}"><br>
    </div>
    PAROL:<br>
    <div>
        <input type="password" class="form-control" name="password"><br>
    </div>
    YENI PAROL:<br>
    <div>
        <input type="password" class="form-control" name="newpass"><br>
    </div>
    <button type="submit" class="btn btn-primary">Yenile</button>
</form>

 
@endsection