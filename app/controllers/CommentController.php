<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Message;
use App\Models\User;
use App\Models\Comment;

/**
 * CommentController
 */
class CommentController extends Controller
{
    public function __construct($params = null)
    {
        $user = new User();

        $user->isSetRemmember_me();

        $this->params = $params;
        $this->model = 'App\Models\Comment';
        parent::__construct($params);
    }

    public function delete($id='', $slug='')
    {
        $comment = new Comment();
        $message = new Message();

        if ($id === '' || $slug === '') {
            $message->setMsg('Error page not found.', 'error');
            Controller::redirect('/post/index');
        }

        $data = $comment->getAuthorOfPostById($id);

        if ((isset($_SESSION['user']) && $_SESSION['user'] === $data[0]['author']) || isset($_SESSION['admin'])) {
            $comment->deleteById($id);

            Controller::redirect('/post/individual/'.$slug);
        } else {
            $message->setMsg('You not authorized.', 'error');
            Controller::redirect('/post/index');
        }
    }
}
