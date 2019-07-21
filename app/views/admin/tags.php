<?php

  use App\Core\Controller;

  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }

  include '../app/views/include/messages.php';
?>

    <div class="container">
        <h3 class="text-center">Tags</h3>
        <table id="tags" class="display" style="width:100%">
            <thead class="bg-dark">
                <tr>
                    <th class="border-right border-white text-white">Id</th>
                    <th class="border-right border-white text-white">Name</th>
                    <th class="border-right border-white text-white">Created</th>
                    <th class="border-right border-white text-white">Delete</th>
                </tr>
            </thead>
            <tfoot class="bg-dark">
                <tr>
                    <th class="border-right border-white text-white">Id</th>
                    <th class="border-right border-white text-white">Name</th>
                    <th class="border-right border-white text-white">Created</th>
                    <th class="border-right border-white text-white">Delete</th>
                </tr>
            </tfoot>
        </table>
        <?php if(isset($_SESSION['admin'])) {?>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark btn-block mt-3 active" data-toggle="modal" data-target="#exampleModal">
                    Create Tag
                </button>
        <?php } ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Tags</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="/tag/create" method="post">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <button type="submit" name="submit" class="btn btn-primary">Add</button>
                </div>
                <input type="text" name="add_tag" placeholder="Add new tag..." class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" required maxlength="20">
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
    $('#tags').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":  "/datatables/tag.php",
        "columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return '<form action="/tag/delete" method="post"><input type="hidden" name="tag_id" value='+row[0]+'><input type="hidden" name="tag_name" value='+row[1]+'><button type="submit" class="btn btn-outline-danger btn-sm">X</button></a></form>';
                    // return '<p class="btn btn-outline-danger delete-tag" data-id="'+row[0]+'" data-name="'+row[1]+'">X</p>';
                },
                "targets": 3
            },
            { "visible": true,  "targets": [ 3 ] }
        ]
    })

    $(document).ready(function () {
        $(".delete-tag").click(function(){
            var id = $(this).data('id') ;
            var name = $(this).data('name') ;
            alert(1);
            alert(id);

            $.ajax({
                     url: "/tag/delete",
                     method:'POST',
                     data:{tag_id:id,tag_name:name},
                     dataType:'JSON',
                     success: function(result){
                         alert('success');
                     }
            });
        });
    });
  </script>
