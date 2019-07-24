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
                <th class="border-right border-white text-white" style="width:20%">Comment</th>
                <th class="border-right border-white text-white" style="width:5%">Article</th>
                <th class="border-right border-white text-white">Author</th>
                <th class="border-right border-white text-white">Created</th>
                <th class="border-right border-white text-white">Actions</th>
            </tr>
        </thead>
        <tfoot class="bg-dark">
            <tr>
                <th class="border-right border-white text-white">Id</th>
                <th class="border-right border-white text-white" style="width:20%">Comment</th>
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
    include '../app/views/include/javascript/comment_datatables.js';
  ?>

    </body>
</html>
