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

    public function __construct(UserConnect $userConnect, Request $request)
    {
        parent::__construct($userConnect,$request);

        $this->_categoryManager=new CategoryManager();
    }

    public function list()
    {
        if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
            $this->AccueilView(true) ;
        }

        $this->listView();
    }
    public function listnotempty()
    {
        echo json_encode( $this->_categoryManager->categoryNotEmpty());
    }
    public function modify()
    {
        $formModify=new CategoryModifyForm($this->_request->getPost());
        try
        {
            $this->valideAccessPage(true,ES_GESTIONNAIRE);

            if($this->_request->hasPost()) {

                $title=$formModify[$formModify::CATEGORY]->text;
                $idHidden=$formModify[$formModify::IDHIDDEN]->text;

                if ($formModify->check() &&
                    !$this->_categoryManager->titleExist($title,$idHidden)) {

                    //récupération de l'userTable
                    $category=$this->_categoryManager->findById ($idHidden);
                    $category->setTitle ($title);
                    $this->_categoryManager->updateCategory($category);
                    $this->flash->writeSucces("La catégorie est modifiée");

                    //Mise à blanc des formulaires
                    $formModify[$formModify::CATEGORY]->text=null;
                    $formModify[$formModify::IDHIDDEN]->text=null;


                } else if (!$formModify->check()){
                    $this->flash->writeError("Les informations sont incorrectes.");
                } else {
                    $this->flash->writeWarning("Cette catégorie existe déjà");
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
        $formDelete=new CategoryDeleteForm($this->_request->getPost());

        if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
            $this->AccueilView(true) ;
        }

        $id=$formDelete[$formDelete::IDHIDDEN]->text;

        if(isset($id) && !$this->_categoryManager->hasArticle($id) ) {
            $this->_categoryManager->deleteCategory($id);
            $this->flash->writeSucces("La catégorie est supprimée.");
            $formDelete[$formDelete ::IDHIDDEN]->text=null;
            $formDelete[$formDelete ::CATEGORY]->text=null;
        } else {
            $this->flash->writeInfo("La suppression est impossible." . $id);
        }

        $this->listView(null,null,$formDelete);
    }
    public function add()
    {
        $formAdd=new CategoryAddForm($this->_request->getPost() );
        try
        {
            if(!$this->valideAccessPage(true,ES_GESTIONNAIRE)) {
                $this->AccueilView(true) ;
            }

            if($this->_request->hasPost()) {

                $category=$formAdd[$formAdd::CATEGORY]->text;

                if ($formAdd->check() &&
                    !$this->_categoryManager->titleExist($category)) {

                    $this->_categoryManager->createCategory($category);
                    $this->flash->writeSucces("La catégorie est ajoutée");
                    $formAdd[$formAdd::CATEGORY]->text=null;


                } else {
                    $this->flash->writeWarning("Cette catégorie existe déjà");
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
            $formAdd=new CategoryAddForm($this->_request->getPost());
        }
        if(is_null( $formModify) ) {
            $formModify=new CategoryModifyForm($this->_request->getPost());
        }
        if(is_null( $formDelete) ) {
            $formDelete=new CategoryDeleteForm($this->_request->getPost());
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