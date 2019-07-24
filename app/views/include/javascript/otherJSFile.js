<script type="text/javascript">
    $(function() {
        var txt = $("input#noSpaces");
        var func = function() {
                   txt.val(txt.val().replace(/\s/g, ''));
                }
        txt.keyup(func).blur(func);
    })

    $('#sortable').sortable({
        update: function (event,ui) {
            let positions = $("#sortable").sortable("toArray");

            $.ajax({
                 url: "position",
                 method:'POST',
                 data:{positions:positions},
                 dataType:'JSON',
                 success: function(result){ }
            });
        }
    })

    $(document).ready(function () {
        $(".update-comment").click(function () {
            $("#id").val( $(this).data('id') );
            $("#slug").val( $(this).data('slug') );
            $("#comment").val( $(this).data('comment') );
        });
    });
</script>
