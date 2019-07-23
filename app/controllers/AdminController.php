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

    public function position()
    {
        $admin = new Admin();

        $positions = $_POST['positions'];

        $num = 1;

        foreach ($positions as $position) {
            $admin->updateArticlesPosition($num, $position);
            $num ++;
        }
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

    //accept comment from admin and publish them
    public function accept()
    {
        $admin = new Admin();
        $message = new Message();

        $is_accepted = $_POST['is_accepted'];
        $id = $_POST['id'];

        $message->setMsg('You create task.', 'success');
        $admin->updateCommentIsAccepted($is_accepted, $id);

        Controller::redirect('/admin/comments');
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

//accept post from admin and publish them
// public function publish() {
//     $is_publish = $_POST['is_publish'];
//     $id = $_POST['id'];
//
//     Admin::updateArticlesIsPublished($is_publish,$id);
//     Controller::redirect('/admin/articles');
// }
