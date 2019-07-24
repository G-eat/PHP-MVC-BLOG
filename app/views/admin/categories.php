<?php

    use App\Core\Controller;

    include '../app/views/include/header.php';
    include '../app/views/include/messages.php';

    if (!isset($_SESSION['admin'])) {
        Controller::redirect('post/index');
    }
?>

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

    <?php if (isset($_SESSION['admin'])) { ?>
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
    include '../app/views/include/javascript/category_datatables.js';
 ?>

    </body>
</html>
