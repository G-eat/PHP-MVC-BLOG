<?php

namespace App\Models;

use App\Core\Controller;
use App\Core\Message;
use App\Database\Database;

if (!isset($_SESSION['admin'])) {
    $message = new Message();

    $message->setMsg('You\'re not authorized.', 'error');
    Controller::redirect('/post/index');
}
/**
 * Admin
 */
class Admin
{
    //accept comment from admin and publish them
    public function accept()
    {
        $message = new Message();

        $is_accepted = $_POST['is_accepted'];
        $id = $_POST['id'];

        $message->setMsg('You complete a task.', 'success');
        $this->updateCommentIsAccepted($is_accepted, $id);

        Controller::redirect('/admin/comments');
    }

    // articles position
    public function position()
    {
        $positions = $_POST['positions'];

        $num = 1;

        foreach ($positions as $position) {
            $this->updateArticlesPosition($num, $position);
            $num ++;
        }
    }

    // accept to be public an article
    public function publish()
    {
        $message = new Message();

        $is_publish = $_POST['is_publish'];
        $id = $_POST['id'];

        $message->setMsg('You create task.', 'success');

        $this->updateArticlesIsPublished($is_publish, $id);

        Controller::redirect('/admin/articles');
    }

    public function getAllArticlesByPosition()
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], null, null, ['position']);
    }

    public function updateArticlesPosition($num, $position)
    {
        $database = new Database();
        return $database->update(['articles'], [['position','=',"'".$num."'"]], [['id','=',"'".$position."'"]]);
    }

    public function updateCommentIsAccepted($is_accepted, $id)
    {
        $database = new Database();
        return $database->update(['comments'], [['accepted','=',"'".$is_accepted."'"]], [['id','=',"'".$id."'"]]);
    }

    public function getArticleById($id)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['id','=',"'".$id."'"]]);
    }

    public function updateArticlesIsPublished($is_publish, $id)
    {
        $database = new Database();
        return $database->update(['articles'], [['is_published','=',"'".$is_publish."'"]], [['id','=',"'".$id."'"]]);
    }
}
