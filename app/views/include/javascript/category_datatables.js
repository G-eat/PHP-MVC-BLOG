<script>
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
                    return '<span class="datatables_css"><a href="/post/category/'+row[1]+'"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></a></span><span class="datatables_css"><a href="/category/edit/'+row[0]+'"><i class="fa text-success fa-pencil fa-lg" aria-hidden="true"></i></a></span><span class="datatables_css"><form onsubmit="return confirm(`Are you sure you want to delete this category?`);" action="/category/delete" method="post"><input type="hidden" name="category_id" value='+row[0]+'><input type="hidden" name="category_name" value='+row[1]+'><button type="submit" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button></a></form></span>';
                },
                "targets": 3
            },
        ]
    });
</script>
