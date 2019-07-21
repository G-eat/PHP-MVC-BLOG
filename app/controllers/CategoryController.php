<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Category;
use App\Core\Message;

if (!isset($_SESSION['admin'])) {
    $message = new Message();

    $message->setMsg('You not authorized.','success');
    Controller::redirect('/post/index');
}

/**
 * Category
 */
class CategoryController extends Controller {
        public function __construct($params = null) {
           $user = new User();

           $user->isSetRemmember_me();

           $this->params = $params;
           $this->model = 'App\Models\Category';
           parent::__construct($params);
        }

        public function change($value='') {
            $category = new Category();

            if (isset($value)) {
                $data = $category->getCategoryNameById($value);

                $this->view('admin\update',[
                  'value' => $data
                ]);
                $this->view->render();
            } else {
                Controller::redirect('/post/index');
            }
        }

}

?>
