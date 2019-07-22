<?php

  use App\Core\Controller;

  include '../app/views/include/header.php';
  include '../app/views/include/messages.php';

  if (!isset($_SESSION['admin'])) {
    Controller::redirect('post/index');
  }
?>

  <div class="container mt-4">
      <h3 class="text-center">Users</h3>
      <table id="users" class="display" style="width:100%">
          <thead class="bg-dark">
              <tr>
                  <th class="border-right border-white text-white">Id</th>
                  <th class="border-right border-white text-white">Username</th>
                  <th class="border-right border-white text-white">Created</th>
              </tr>
          </thead>
          <tfoot class="bg-dark">
              <tr>
                  <th class="border-right border-white text-white">Id</th>
                  <th class="border-right border-white text-white">Username</th>
                  <th class="border-right border-white text-white">Created</th>
              </tr>
          </tfoot>
      </table>
  </div>

 <?php
    include '../app/views/include/footer.php';
  ?>
  <script type="text/javascript">
    $('#users').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":  "/datatables/user.php",
        "columnDefs": [
            {
                // The `data` parameter refers to the data for the cell (defined by the
                // `data` option, which defaults to the column being worked with, in
                // this case `data: 0`.
                "render": function ( data, type, row ) {
                    return '<a href="/post/user/'+row[1]+'">'+row[1]+'</a>';
                    // return '<p class="btn btn-outline-danger delete-tag" data-id="'+row[0]+'" data-name="'+row[1]+'">X</p>';
                },
                "targets": 1
            }
        ]
    });
  </script>
