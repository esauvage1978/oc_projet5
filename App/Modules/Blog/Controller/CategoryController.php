<?php

namespace ES\App\Modules\Blog\Controller;

use ES\App\Modules\Blog\Form\CategoryAddForm;
use ES\App\Modules\Blog\Form\CategoryModifyForm;
use ES\App\Modules\Blog\Form\CategoryDeleteForm;
use ES\App\Modules\Blog\Model\CategoryManager;

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

    private $_categoryManager;

    public function __construct(UserConnect $userConnect, Request $request,$flash, $renderView)
    {
        parent::__construct($userConnect,$request,$flash, $renderView);

        $this->_categoryManager=new CategoryManager();
    }

    public function list()
    {
        $this->listView();
    }
    public function listnotempty()
    {
        echo json_encode( $this->_categoryManager->categoryNotEmpty());
    }
    public function modify()
    {
        $formModify=new CategoryModifyForm($this->request->getPost());
        try
        {
            if($this->request->hasPost()) {

                $title=$formModify[$formModify::CATEGORY]->getText();
                $idHidden=$formModify[$formModify::IDHIDDEN]->getText();

                if ($formModify->check() &&
                    !$this->_categoryManager->titleExist($title,$idHidden)) {

                    //récupération de l'UserTable
                    $category=$this->_categoryManager->findById ($idHidden);
                    $category->setTitle ($title);
                    $this->_categoryManager->updateCategory($category);
                    $formModify[$formModify::MESSAGE]->showMessage("La catégorie est modifiée.");


                    //Mise à blanc des formulaires
                    $formModify[$formModify::CATEGORY]->setText('');
                    $formModify[$formModify::IDHIDDEN]->setText('');


                } else if (!$formModify->check()){
                    $formModify[$formModify::MESSAGE]->showMessage("Les informations sont incorrectes.",false);

                } else {
                    $formModify[$formModify::MESSAGE]->showMessage("Cette catégorie existe déjà.",false);

                }

            }

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->listView(null,$formModify);

    }

    public function delete()
    {
        $formDelete=new CategoryDeleteForm($this->request->getPost());

        $id=$formDelete[$formDelete::IDHIDDEN]->getText();

        if(isset($id) && !$this->_categoryManager->hasArticle($id) ) {
            $this->_categoryManager->deleteCategory($id);
            $this->flash->writeSucces("La catégorie est supprimée.");
            $formDelete[$formDelete ::IDHIDDEN]->setText(null);
            $formDelete[$formDelete ::CATEGORY]->setText(null);
        } else {
            $this->flash->writeInfo("La suppression est impossible." . $id);
        }

        $this->listView(null,null,$formDelete);
    }
    public function add()
    {
        $formAdd=new CategoryAddForm($this->request->getPost() );
        try
        {
            if($this->request->hasPost()) {

                $category=$formAdd[$formAdd::CATEGORY]->getText();

                if ($formAdd->check() &&
                    !$this->_categoryManager->titleExist($category)) {

                    $this->_categoryManager->createCategory($category);
                    $formAdd[$formAdd::MESSAGE]->showMessage("La catégorie est ajoutée");
                    $formAdd[$formAdd::CATEGORY]->setText('');
                } else {
                    $formAdd[$formAdd::MESSAGE]->showMessage("Cette catégorie existe déjà",false);
                }
            }

        }
        catch(\InvalidArgumentException $e)
        {
            $this->flash->writeError( $e->getMessage());
            $this->AccueilView();
        }

        $this->listView($formAdd,null,null);

    }
    public function listView($formAdd=null, $formModify=null,$formDelete=null)
    {
        if(is_null( $formAdd) ) {
            $formAdd=new CategoryAddForm($this->request->getPost());
        }
        if(is_null( $formModify) ) {
            $formModify=new CategoryModifyForm($this->request->getPost());
        }
        if(is_null( $formDelete) ) {
            $formDelete=new CategoryDeleteForm($this->request->getPost());
        }

        $list=$this->_categoryManager->getCategorys();

        $params=['title'=>'Blog',
            'list'=>$list,
            'formDelete'=>$formDelete,
            'formAdd'=>$formAdd,
            'formModify'=>$formModify];
        $this->view('CategoryCRUDView',$params);

    }
}