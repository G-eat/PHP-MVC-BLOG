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
                  return '<a href="/post/user/'+row[1]+'"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a>';
              },
              "targets": 3
          }
      ]
  });
</script>
