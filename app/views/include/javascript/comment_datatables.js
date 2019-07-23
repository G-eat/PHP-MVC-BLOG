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
                },
                "targets": 2
            },
            {
                "render": function ( data, type, row ) {
                    return '<a href="/post/user/'+row[2]+'">'+row[2]+'</a>';
                },
                "targets": 3
            },
            {
                "render": function ( data, type, row ) {
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
