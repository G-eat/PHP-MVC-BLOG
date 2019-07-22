<?php

  use App\Core\Controller;

  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

<div class="container">
    <h3 class="text-center">Comments</h3>
    <table id="comments" class="display" style="width:100%">
        <thead class="bg-dark">
            <tr>
                <th class="border-right border-white text-white">Id</th>
                <th class="border-right border-white text-white">Comment</th>
                <th class="border-right border-white text-white" style="width:5%">Article</th>
                <th class="border-right border-white text-white">Author</th>
                <th class="border-right border-white text-white">Created</th>
                <th class="border-right border-white text-white">Actions</th>
            </tr>
        </thead>
        <tfoot class="bg-dark">
            <tr>
                <th class="border-right border-white text-white">Id</th>
                <th class="border-right border-white text-white">Comment</th>
                <th class="border-right border-white text-white" style="width:5%">Article</th>
                <th class="border-right border-white text-white">Author</th>
                <th class="border-right border-white text-white">Created</th>
                <th class="border-right border-white text-white">Actions</th>
            </tr>
        </tfoot>
    </table>
</div>



 <?php
    include '../app/views/include/footer.php';
  ?>

  <script type="text/javascript">
    $('#comments').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":  "/datatables/comment.php",
        "columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return '<a href="/admin/post/'+row[5]+'">Article</a>';
                    // return '<p class="btn btn-outline-danger delete-tag" data-id="'+row[0]+'" data-name="'+row[1]+'">X</p>';
                },
                "targets": 2
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return '<a href="/post/user/'+row[2]+'">'+row[2]+'</a>';
                    // return '<p class="btn btn-outline-danger delete-tag" data-id="'+row[0]+'" data-name="'+row[1]+'">X</p>';
                },
                "targets": 3
            },
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                    "render": function ( data, type, row ) {
                        // return row[4];
                        if (row[3] === 'pending'){
                            return '<form action="/admin/accept" method="post"><input class="btn btn-success" type="submit" name="is_accepted" value="Accepted"><input class="btn btn-danger" type="submit" name="is_accepted" value="Reject"><input type="hidden" name="id" value='+row[0]+'"></form>';
                        } else if(row[3] == 'Accepted') {
                         return '<p class="text-success">Accept</p>';
                        } else {
                         return '<p class="text-danger">Reject</p>';
                        }
                },
                "targets": 5
            }
        ]
    });
  </script>
