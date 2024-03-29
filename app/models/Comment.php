<?php

namespace App\Models;

use App\Core\Controller;
use App\Core\Message;
use App\Database\Database;

/**
 * Comment
 */
class Comment
{
    public function create()
    {
        $message = new Message();
        $database = new Database();

        if (!isset($_SESSION['user'])) {
            $message->setMsg("You're not logIn.", 'error');
            Controller::redirect('/post/index');
        }

        if (isset($_POST['comment']) && (trim($_POST['comment']) !== '')) {
            $article_slug = $_POST['article_slug'];

            $this->insertCommment($_POST['comment'], $_POST['author'], $_POST['article_id']);

            if (isset($_SESSION['admin'])) {
                $this->adminCommentAccept($_POST['article_id'], $article_slug);
            }

            $message->setMsg('You create the comment,now admin need to accept that.', 'success');
            Controller::redirect('/post/individual/'.$article_slug);
        } else {
            $message->setMsg('Empty Comment.', 'error');
            Controller::redirect('/post/index');
        }
    }

    public function adminCommentAccept($article_id, $article_slug)
    {
        $database = new Database();
        $message = new Message();

        $data = $database->update(['comments'], [['accepted','=','"Accepted"']], [['article_id','=',$article_id]]);
        $message->setMsg('You comment.', 'success');
        Controller::redirect('/post/individual/'.$article_slug);
    }

    public function update()
    {
        $message = new Message();
        $database = new Database();

        if (isset($_POST['submit']) && (trim($_POST['update_comment']) !== '')) {
            if (!isset($_SESSION['user']) || $_POST['author'] !== $_SESSION['user']) {
                Controller::redirect('/post/index');
            }

            $this->updateAcceptedColumnWhereCommentIsUpdated($_POST['update_comment'], $_POST['update_id']);

            if (isset($_SESSION['admin'])) {
                $this->adminUpdateCommentAccept($_POST['update_id'], $_POST['comment_slug']);
            }

            $message->setMsg('You update the comment,now admin need to accept that.', 'success');
            Controller::redirect('/post/individual/'.$_POST['comment_slug']);
        } else {
            $message->setMsg('Empty Comment.', 'error');
            Controller::redirect('/post/index');
        }
    }

    public function adminUpdateCommentAccept($update_id, $comment_slug)
    {
        $database = new Database();
        $message = new Message();

        $data = $database->update(['comments'], [['accepted','=','"Accepted"']], [['id','=', $update_id]]);
        $message->setMsg('You update comment.', 'success');
        Controller::redirect('/post/individual/'.$comment_slug);
    }

    public function delete()
    {
        $message = new Message();

        $id = $_POST['id'];
        $slug = $_POST['slug'];

        if ($id === '' || $slug === '') {
            $message->setMsg('Error page not found.', 'error');
            Controller::redirect('/post/index');
        }

        $data = $this->getAuthorOfPostById($id);

        if ((isset($_SESSION['user']) && $_SESSION['user'] === $data[0]['author']) || isset($_SESSION['admin'])) {
            $this->deleteById($id);

            Controller::redirect('/post/individual/'.$slug);
        } else {
            $message->setMsg('You not authorized.', 'error');
            Controller::redirect('/post/index');
        }
    }
    
    public function insertCommment($comment, $author, $article_id)
    {
        $database = new Database();
        return $database->insert(['comments'], ['comment','author','article_id'], ["'".$comment."'","'".$author."'","'".$article_id."'"]);
    }

    public function getAuthorOfPostById($id)
    {
        $database = new Database();
        return $database->select(['author'], ['comments'], [['id','=',"'".$id."'"]]);
    }

    public function deleteById($id)
    {
        $database = new Database();
        $message = new Message();

        $message->setMsg("Your're deleted comment.", 'error');

        return $database->delete(['comments'], [['id','=',"'".$id."'"]]);
    }

    public function updateAcceptedColumnWhereCommentIsUpdated($comment, $comment_id)
    {
        $database = new Database();
        return $database->update(['comments'], [['comment','=',"'".$comment."'"],['accepted','=',"'pending'"]], [['id','=',"'".$comment_id."'"]]);
    }
}
