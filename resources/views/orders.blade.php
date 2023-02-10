@extends('layouts.app')

@section('orders')
<!DOCTYPE html>
<html>
<head>
  <title>Laravel 8 DataTable Ajax Books CRUD Example</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>


</head>
<body>

<div class="container mt-4">
  
  <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Add</button></div>
  <div><a class="col-md-12 mt-1 mb-2" href="/oexport">EXCEL</a></div>

  <div class="card">

    <div class="card-header text-center font-weight-bold">
      <h2>ORDERS</h2>
    </div>

    <div class="card-body">

        <table class="table table-bordered" id="datatable-ajax-crud">
           <thead>
              <tr>
                <th>Id</th>
                <th>MUSTERI</th>
                <th>MEHSUL</th>
                <th>BREND</th>
                <th>ALISH</th>
                <th>SATISH</th>
                <th>STOK</th>
                <th>Miqdar</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
           </thead>
        </table>

    </div>

  </div>
  <!-- boostrap add and edit book model -->
    <div class="modal fade" id="ajax-book-model" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ajaxBookModel"></h4>
          </div>
          <div class="modal-body">
            <form action="javascript:void(0)" id="addEditBookForm" name="addEditBookForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">MUSTERI</label>
                <select name="client_id" class="form-control">
                    <option value="">Musteri secin</option>
                        @foreach($cdata as $cinfo)
                            <option value="{{$cinfo->id}}">{{$cinfo->client}} {{$cinfo->soyad}}</option>
                        @endforeach
                </select>
              </div>  

              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">MEHSUL</label>
                <select name="product_id" class="form-control">
                    <option value="">Mehsulu secin</option>
                        @foreach($pdata as $pinfo)
                            <option value="{{$pinfo->id}}">{{$pinfo->mehsul}}</option>
                        @endforeach
                </select>
              </div>  
              
              <div class="form-group">
                <label class="col-sm-2 control-label">SIFARISH</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="sifarish" name="sifarish" placeholder="Enter Sifarish" required="">
                </div>
              </div>                  

              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Save changes
                </button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            
          </div>
        </div>
      </div>
    </div>
<!-- end bootstrap model -->

<script type="text/javascript">
     
 $(document).ready( function () {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#image').change(function(){
           
    let reader = new FileReader();

    reader.onload = (e) => { 

      $('#preview-image').attr('src', e.target.result); 
    }

    reader.readAsDataURL(this.files[0]); 
  
   });

    $('#datatable-ajax-crud').DataTable({
           processing: true,
           serverSide: true,
           ajax: "{{ url('/') }}",
           columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    { data: 'client', name: 'client' },
                    { data: 'mehsul', name: 'mehsul' },
                    { data: 'brand', name: 'brand' },
                    { data: 'alish', name: 'alish' },
                    { data: 'satish', name: 'satish' },
                    { data: 'miqdar', name: 'miqdar' },
                    { data: 'sifarish', name: 'sifarish' },
                    { data: 'created_at', name: 'created_at' },
                    {data: 'action', name: 'action', orderable: false},
                 ],
          order: [[0, 'desc']]
    });


    $('#addNewBook').click(function () {
       $('#addEditBookForm').trigger("reset");
       $('#ajaxBookModel').html("Add Book");
       $('#ajax-book-model').modal('show');
       $("#image").attr("required", "true");
       $('#id').val('');
       $('#preview-image').attr('src', 'https://www.riobeauty.co.uk/images/product_image_not_found.gif');


    });
 
    $('body').on('click', '.edit', function () {

        var id = $(this).data('id');
         
        // ajax
        $.ajax({
            type:"POST",
            url: "{{ url('oredit') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
              $('#ajaxBookModel').html("Edit Book");
              $('#ajax-book-model').modal('show');
              $('#id').val(res.id);
              $('#client_id').val(res.client_id);
              $('#product_id').val(res.product_id);
              $('#sifarish').val(res.sifarish);

           }
        });

    });

    $('body').on('click', '.delete', function () {

       if (confirm("Delete Record?") == true) {
        var id = $(this).data('id');
         
        // ajax
        $.ajax({
            type:"POST",
            url: "{{ url('ordelete') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){

              var oTable = $('#datatable-ajax-crud').dataTable();
              oTable.fnDraw(false);
           }
        });
       }

    });

   $('#addEditBookForm').submit(function(e) {

     e.preventDefault();
  
     var formData = new FormData(this);
  
     $.ajax({
        type:'POST',
        url: "{{ url('orstore')}}",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
          $("#ajax-book-model").modal('hide');
          var oTable = $('#datatable-ajax-crud').dataTable();
          oTable.fnDraw(false);
          $("#btn-save").html('Submit');
          $("#btn-save"). attr("disabled", false);
        },
        error: function(data){
           console.log(data);
         }
       });
   });
});


$('body').on('click', '.tesdiq', function () {

if (confirm("Tesdiq etmeye razisiz?") == true) {
 var id = $(this).data('id');
  
 // ajax
 $.ajax({
     type:"POST",
     url: "{{ url('ortesdiq') }}",
     data: { id: id },
     dataType: 'json',
     success: function(res){
      if(res=='Bazada kifayet qeder mehsul yoxdur!')
      {alert(res)}
      else
      {
      var oTable = $('#datatable-ajax-crud').dataTable();
       oTable.fnDraw(false);
      }
    }
 });
}

});



$('body').on('click', '.legv', function () {

if (confirm("Legv edilsin?") == true) {
 var id = $(this).data('id');
  
 // ajax
 $.ajax({
     type:"POST",
     url: "{{ url('orlegv') }}",
     data: { id: id },
     dataType: 'json',
     success: function(res){
      var oTable = $('#datatable-ajax-crud').dataTable();
       oTable.fnDraw(false);
      
    }
 });
}

});
</script>
</div>  
</body>
</html>

@endsection