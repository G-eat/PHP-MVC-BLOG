<?php

namespace App\Models;

use App\Core\Controller;
use App\Core\Data;
use App\Core\Message;
use App\Database\Database;

/**
 * articles/post
 */
class Post
{
    public function slug($text='')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


    public function create()
    {
        $database = new Database();
        $message = new Message();
        $data = new Data();

        if ($_FILES['image']['size'] > 1388608 || $_FILES['image']['size'] == 0) {
            $message->setMsg('You\'re photo is too large.', 'error');
            Controller::redirect('/post/index');
        }

        $slug = "'".$this->slug($_POST['slug'])."'";
        $check = $this->seeIfArticleSlugExist($slug);

        if ($check == 1) {
            $data->setData($_POST['title'], 'title');
            $data->setData($this->slug($_POST['slug']), 'slug');
            $data->setData($_POST['body-editor1'], 'body');
            $data->setData($_POST['category'], 'category');

            $message->setMsg('This slug exist,try different slug.', 'error');
            Controller::redirect('/post/createpost');
        } else {
            $image = $this->uploadPhoto($_FILES['image']['name'], $_FILES['image']['size']);

            $this->insertTag($_POST['tags'], $slug);
            $database->insert(
                ['articles'],
                ['author','title','body','slug','category','file_name'],
                ["'".$_SESSION['user']."'","'".$_POST['title']."'","'".$_POST['body-editor1']."'",$slug,"'".$_POST['category']."'","'".$image."'" ]
                );

            if (isset($_SESSION['admin'])) {
                $this->updateIs_PublishedWhenAdminCreatePost($slug);
            }

            $message->setMsg('You create the post,now admin need to accept that.', 'success');
            Controller::redirect('/post/index');
        }
    }

    public function updateIs_PublishedWhenAdminCreatePost($slug)
    {
        $database = new Database();
        $message = new Message();

        $database->update(['articles'], [['is_published','=','"Publish"']], [['slug','=',$slug]]);
        $message->setMsg('You create the post', 'success');
        Controller::redirect('/post/index');
    }

    public function delete()
    {
        $database = new Database();
        $message = new Message();

        if ((isset($_SESSION['user']) && $_POST['author'] == $_SESSION['user']) || isset($_SESSION['admin'])) {
            $id = $_POST['id'];
            $slug = $_POST['slug'];
            $author = isset($_POST['author']) ? $_POST['author']:'';
            $page = isset($_POST['page']) ? $_POST['page']:'';
            $order = isset($_POST['order']) ? $_POST['order']:'';
            $page_current = isset($_POST['page_current']) ? $_POST['page_current']:'';

            $file_name = $database->select(['file_name'], ['articles'], [['id','=',"'".$id."'"]]);

            unlink('.\postPhoto\\'.$file_name[0]['file_name']);
            unlink('.\originalPostPhoto\\'.$file_name[0]['file_name']);

            $database->delete(['comments'], [['article_id','=',"'".$id."'"]]);
            $database->delete(['articles'], [['id','=',"'".$id."'"]]);
            $database->delete(['articles_tag'], [['article_slug','=',"'".$slug."'"]]);


            $message->setMsg('You deleted the post.', 'error');

            if ($page !== '') {
                Controller::redirect('/post/index/'.$order.'/'.$page_current);
            }

            if (isset($_SESSION['admin']) && $author == '') {
                Controller::redirect('/admin/articles');
            }

            Controller::redirect('/post/user/'.$author);
        } else {
            $message->setMsg("Your're not authorized.", 'error');
            Controller::redirect('/post/index');
        }
    }

    public function articlesWithThisTagPublished($tag)
    {
        $database = new Database();

        $mysql = 'SELECT DISTINCT articles.*,articles_tag.article_slug
                  FROM articles_tag INNER JOIN articles ON articles_tag.article_slug =articles.slug
                  WHERE articles_tag.tag_name = "'.$tag.'" AND articles.is_published = "publish"';
        return  $database->raw($mysql);
    }

    public function getArticles($order, $by, $limit_from, $to)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['is_published','=','"Publish"']], null, [$order,$by], [$limit_from,$to]);
    }

    public function nrPageOfArticle()
    {
        $database = new Database();

        $all_articles = $database->select(['*'], ['articles'], [['is_published','=','"Publish"']]);

        return ceil(count($all_articles)/5);
    }

    public function returnError($articles)
    {
        if ($articles == null) {
            return 'Error! There are no link like this.';
        } else {
            return '';
        }
    }

    public function limitFrom($id)
    {
        if ($id == '' || $id == 1) {
            return 0;
        } else {
            return ($id - 1) * 5;
        }
    }

    public function seeIfArticleSlugExist($slug)
    {
        $database = new Database();

        $mysql = 'SELECT * FROM `articles` WHERE `slug` = '.$slug;

        return count($database->raw($mysql));
    }

    public function uploadPhoto($image, $size)
    {
        $message = new Message();

        $image = uniqid('', true) . '-' .$_FILES['image']['name'];

        $file_destination = '.\postPhoto\\'.$image;
        // // Compress Image
        $this->compressImage($_FILES['image']['tmp_name'], $file_destination, 60);
        $file_destination_original_photo = '.\originalPostPhoto\\'.$image;
        move_uploaded_file($_FILES['image']['tmp_name'], $file_destination_original_photo);

        return $image;
    }

    // Compress image
    public function compressImage($source, $destination, $quality)
    {
        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        }

        imagejpeg($image, $destination, $quality);
    }

    public function insertTag($tags, $slug)
    {
        $database = new Database();

        foreach ($tags as $tag) {
            $database->insert(['articles_tag'], ['tag_name','article_slug'], ["'".$tag."'",$slug]);
        }
    }

    public function insertArticles($author, $title, $body, $slug, $category, $image)
    {
        $database = new Database();

        $database->insert(
            ['articles'],
            ['author','title','body','slug','category','file_name'],
            ["'".$author."'","'".$title."'","'".$body."'",$slug,"'".$category."'","'".$image."'" ]
        );
    }

    public function getArticleWithThisSlug($slug)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['slug','=',"'".$slug."'"]]);
    }

    public function seeIfArticleIsPublished($slug, $article)
    {
        $database = new Database();

        $article_ispublish = $database->select(['*'], ['articles'], [['slug','=',"'".$slug."'"],['AND'],['is_published','=','"Publish"']]);

        if (count($article_ispublish) == 0 && $article[0]['author'] !== $_SESSION['user']) {
            Controller::redirect('/post/index');
        }
    }

    public function articleAuthor($author)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['author','=',"'".$author."'"],['AND'],['is_published','=','"Publish"']], null, null, ['10']);
    }

    public function tagsWithSameSlug($slug)
    {
        $database = new Database();
        return $database->select(['*'], ['articles_tag'], [['article_slug','=',"'".$slug."'"]]);
    }

    public function commentAccepted($article_id)
    {
        $database = new Database();
        return $database->select(['*'], ['comments'], [['article_id','=',"'".$article_id."'"],['AND'],['accepted','=','"Accepted"']]);
    }

    public function getArticlesWithThisAuthor($name, $limit_from)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['author','=',"'".$name."'"]], null, null, [$limit_from,'5']);
    }

    public function nrPageOfArticleWithThisAuthor($name)
    {
        $database = new Database();

        $all_articles = $database->select(['*'], ['articles'], [['author','=',"'".$name."'"]]);

        return ceil(count($all_articles)/5);
    }

    public function getArticlesWithThisAuthorPublished($name, $limit_from)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']], null, null, [$limit_from,'5']);
    }

    public function nrPageOfArticleWithThisAuthorPublished($name)
    {
        $database = new Database();

        $all_articles = $database->select(['*'], ['articles'], [['author','=',"'".$name."'"],['AND'],['is_published','=','"Publish"']]);

        return ceil(count($all_articles)/5);
    }

    public function getArticlesWithThisCategoryPublished($category)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['category','=',"'".$category."'"],['AND'],['is_published','=',"'Publish'"]]);
    }

    public function category_articles($articles_category)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['category','=',"'".$articles_category."'"],['AND'],['is_published','=',"'Publish'"]], null, null, ['5']);
    }

    public function getArticlesWhereTitleLike($search, $limit_from)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']], null, null, [$limit_from,'5']);
    }

    public function getNrPageWhereTitleLike($search)
    {
        $database = new Database();

        $all_articles = $database->select(['*'], ['articles'], [['title','LIKE','"%'.$search.'%"'],['AND'],['is_published','=','"Publish"']]);

        return ceil(count($all_articles)/5);
    }

    public function getElement($articles_id)
    {
        $database = new Database();
        return $database->select(['*'], ['articles'], [['id'.'=',"'".$articles_id."'"]]);
    }
}
