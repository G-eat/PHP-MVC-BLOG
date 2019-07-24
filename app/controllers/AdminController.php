<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Message;
use App\Database\Database;
use App\Models\User;
use App\Models\Admin;

if (!isset($_SESSION['admin'])) {
    $message = new Message();

    $message->setMsg('You not authorized.', 'error');
    Controller::redirect('/post/index');
}

/**
 * AdminController
 */
class AdminController extends Controller
{
    public function __construct($params = null)
    {
        $user = new User();

        $user->isSetRemmember_me();

        $this->params = $params;
        $this->model = 'App\Models\Admin';
        parent::__construct($params);
    }

    public function index()
    {
        $this->view('admin\index', []);
        $this->view->render();
    }

    public function categories()
    {
        $this->view('admin\categories', []);
        $this->view->render();
    }

    public function users()
    {
        $this->view('admin\users', []);
        $this->view->render();
    }

    public function articles()
    {
        $admin = new Admin();

        $data = $admin->getAllArticlesByPosition();

        $this->view('admin\articles', [
          'articles' => $data
        ]);
        $this->view->render();
    }

    public function comments()
    {
        $database = new Database();

        $comments = $database->select(['*'], ['comments']);

        $this->view('admin\comments', [
          'comments' => $comments
        ]);
        $this->view->render();
    }

    public function post($id = '')
    {
        $admin = new Admin();
        $message = new Message();

        $data = $admin->getArticleById($id);

        if ($data == null || $id == '') {
            $message->setMsg('Error page not found.', 'error');
            Controller::redirect('/post/index');
        }

        Controller::redirect('/post/individual/'.$data[0]['slug']);
    }

    public function tags()
    {
        $this->view('admin\tags', []);
        $this->view->render();
    }
}
