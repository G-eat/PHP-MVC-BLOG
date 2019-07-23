<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

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
}
