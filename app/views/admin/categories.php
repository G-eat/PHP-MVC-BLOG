<?php

  use App\Core\Controller;

  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>
<style media="screen">
    span {
        display:inline-block;
        margin-right: 10px;
    }
</style>

<div class="container">
    <h3 class="text-center">Categories</h3>
    <table id="categories" class="display" style="width:100%">
        <thead class="bg-dark">
            <tr>
                <th class="border-right border-white text-white">Id</th>
                <th class="border-right border-white text-white">Name</th>
                <th class="border-right border-white text-white">Created</th>
                <th class="border-right border-white text-white">Actions</th>
            </tr>
        </thead>
        <tfoot class="bg-dark">
            <tr>
                <th class="border-right border-white text-white">Id</th>
                <th class="border-right border-white text-white">Name</th>
                <th class="border-right border-white text-white">Created</th>
                <th class="border-right border-white text-white">Actions</th>
            </tr>
        </tfoot>
    </table>
    <?php if(isset($_SESSION['admin'])) {?>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-dark btn-block mt-3 active" data-toggle="modal" data-target="#exampleModal">
          ADD Category
        </button>
    <?php } ?>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/category/create" method="post">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <button type="submit" name="submit" class="btn btn-primary">Add</button>
            </div>
            <input type="text" name="add_category" placeholder="Add new category..." class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required maxlength="20">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
 <?php
    include '../app/views/include/footer.php';
  ?>
  <script type="text/javascript">
    $('#categories').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":  "/datatables/category.php",
        "columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return '<span><a href="/post/category/'+row[1]+'"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a></span><span><a href="/category/change/'+row[0]+'"><i class="fa text-success fa-pencil fa-lg" aria-hidden="true"></i></a></span><span><form onsubmit="return confirm(`Are you sure you want to delete this category?`);" action="/category/delete" method="post"><input type="hidden" name="category_id" value='+row[0]+'><input type="hidden" name="category_name" value='+row[1]+'><button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button></a></form></span>';
                    // return '<p class="btn btn-outline-danger delete-tag" data-id="'+row[0]+'" data-name="'+row[1]+'">X</p>';
                },
                "targets": 3
            },
            // {
            //     // The `data` parameter refers to the data for the cell (defined by the
            //     // `data` option, which defaults to the column being worked with, in
            //     // this case `data: 0`.
            //     "render": function ( data, type, row ) {
            //         return '<a href="/category/change/'+row[0]+'">'+row[1]+'</a>';
            //         // return '<p class="btn btn-outline-danger delete-tag" data-id="'+row[0]+'" data-name="'+row[1]+'">X</p>';
            //     },
            //     "targets": 1
            // }
        ]
    });
  </script>
