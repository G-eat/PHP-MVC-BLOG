<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Message;
use App\Models\User;
use App\Models\Category;

if (!isset($_SESSION['admin'])) {
    $message = new Message();

    $message->setMsg('You not authorized.', 'error');
    Controller::redirect('/post/index');
}

/**
 * CategoryController
 */
class CategoryController extends Controller
{
    public function __construct($params = null)
    {
        $user = new User();

        $user->isSetRemmember_me();

        $this->params = $params;
        $this->model = 'App\Models\Category';
        parent::__construct($params);
    }

    public function edit($id='')
    {
        $category = new Category();
        $message = new Message();

        if ($id !== '') {
            $data = $category->getCategoryNameById($id);

            if ($data == null) {
                $message->setMsg('Error page not found.', 'error');
                Controller::redirect('/post/index');
            }

            $this->view('category\update', [
                'value' => $data
            ]);
            $this->view->render();
        } else {
            $message->setMsg('Error page not found.', 'error');
            Controller::redirect('/post/index');
        }
    }
}
