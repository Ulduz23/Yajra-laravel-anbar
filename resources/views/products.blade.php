@extends('layouts.app')

@section('products')

<!DOCTYPE html>
<html>
<head>
  <title>Laravel 8 DataTable Ajax Books CRUD Example</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

</head>
<body>

<div class="container mt-4">
  
  <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Add</button></div>
  <div><a class="col-md-12 mt-1 mb-2" href="/pexport">EXCEL</a></div>

  <div class="card">

    <div class="card-header text-center font-weight-bold">
      <h2>PRODUCTS</h2>
    </div>

    <div class="card-body">

        <table class="table table-bordered" id="datatable-ajax-crud">
           <thead>
              <tr>
                 <th>Id</th>
                 <th>Image</th>
                 <th>BREND</th>
                 <th>MEHSUL</th>
                 <th>ALISH</th>
                 <th>SATISH</th>
                 <th>MIQDAR</th>
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
                <label for="name" class="col-sm-2 control-label">BRAND</label>
                <select name="brand_id" class="form-control">
                    <option value="">Brendi secin</option>
                        @foreach($bdata as $binfo)
                            <option value="{{$binfo->id}}">{{$binfo->brand}}</option>
                        @endforeach
                </select>
              </div>  

              <div class="form-group">
                <label for="name" class="col-sm-2 control-label">MEHSUL</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="mehsul" name="mehsul" placeholder="Enter Mehsul" maxlength="50" required="">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">ALISH</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="alish" name="alish" placeholder="Enter Alish" required="">
                </div>
              </div>    
              
              
              <div class="form-group">
                <label class="col-sm-2 control-label">SATISH</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="satish" name="satish" placeholder="Enter Satish" required="">
                </div>
              </div>  
              
              <div class="form-group">
                <label class="col-sm-2 control-label">MIQDAR</label>
                <div class="col-sm-12">
                  <input type="text" class="form-control" id="miqdar" name="miqdar" placeholder="Enter Miqdar" required="">
                </div>
              </div>                  

               <div class="form-group">
                <label class="col-sm-2 control-label">Image</label>
                <div class="col-sm-6 pull-left">
                  <input type="file" class="form-control" id="image" name="image" required="">
                </div>               
                <div class="col-sm-6 pull-right">
                  <img id="preview-image" src="https://www.riobeauty.co.uk/images/product_image_not_found.gif"
                        alt="preview image" style="max-height: 250px;">
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
           ajax: "{{ url('products') }}",
           columns: [
                    {data: 'id', name: 'id', 'visible': false},
                    { data: 'image', name: 'image' , orderable: false},
                    { data: 'brand', name: 'brand' },
                    { data: 'mehsul', name: 'mehsul' },
                    { data: 'alish', name: 'alish' },
                    { data: 'satish', name: 'satish' },
                    { data: 'miqdar', name: 'miqdar' },
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
            url: "{{ url('pedit') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
              $('#ajaxBookModel').html("Edit Book");
              $('#ajax-book-model').modal('show');
              $('#id').val(res.id);
              $('#brand_id').val(res.brand_id);
              $('#mehsul').val(res.mehsul);
              $('#alish').val(res.alish);
              $('#satish').val(res.satish);
              $('#miqdar').val(res.miqdar);
              $('#image').removeAttr('required');

           }
        });

    });

    $('body').on('click', '.delete', function () {

       if (confirm("Delete Record?") == true) {
        var id = $(this).data('id');
         
        // ajax
        $.ajax({
            type:"POST",
            url: "{{ url('pdelete') }}",
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
        url: "{{ url('pstore')}}",
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
</script>
</div>  
</body>
</html>

@endsection