<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\Blog\Model\CommentManager;

use \ES\Core\Controller\AbstractController;
use ES\App\Modules\User\Model\UserConnect;
use \ES\Core\Toolbox\Request;


/**
 * CategoryController short summary.
 *
 * CategoryController description.
 *
 * @version 1.0
 * @author ragus
 */
class CategoryController extends AbstractController
{
    static $module='Blog';

    private $_commentManager;

    public function __construct(UserConnect $userConnect, Request $request)
    {
        parent::__construct($userConnect,$request);
    }
}