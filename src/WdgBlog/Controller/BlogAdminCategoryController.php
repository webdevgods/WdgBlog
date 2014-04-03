<?php
namespace WdgBlog\Controller;

use Zend\View\Model\ViewModel;

class BlogAdminCategoryController extends AbstractActionController
{
    protected $blogService;
    
    public function listAction()
    {
        if(!$this->isUserAuthorized())return $this->redirectToLogin();
        
        $page       = (int) $this->params()->fromRoute('page', 0);
        $paginator  = $this->getBlogService()->getCategoriesPaginator($page, 10);
        
        if($paginator->count() >0 && $paginator->count() < $page)
            $this->redirect()->toRoute("wdgadmin/blog_category_list");
        
        return new ViewModel(array("paginator" => $paginator));
    }
    
    public function showAction()
    {
        if(!$this->isUserAuthorized())return $this->redirectToLogin();
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        return new ViewModel(array("category" => $this->getBlogService()->getCategoryById($id)));
    }
    
    public function addAction()
    {
        if(!$this->isUserAuthorized())return $this->redirectToLogin();
        
        $service    = $this->getBlogService();
        $form       = $service->getAddCategoryForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Category = $service->addCategoryByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Added Category");

                return $this->redirect()->toRoute("wdgadmin/blog_category_show", array("id" => $Category->getId()));
            }
            catch (\WdgBlog\Exception\Service\Blog\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not add category: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function editAction()
    {
        if(!$this->isUserAuthorized())return $this->redirectToLogin();
        
        $service    = $this->getBlogService();
        $form       = $service->getEditCategoryForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Category = $service->EditCategoryByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Edited Category");

                return $this->redirect()->toRoute("wdgadmin/blog_category_show", array("id" => $Category->getId()));
            }
            catch (\WdgBlog\Exception\Service\Blog\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not edit category: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    /**
     * getBlogService
     *
     * @return \WdgBlog\Service\Blog
     */
    public function getBlogService()
    {
        if (null === $this->blogService)
        {
            $this->blogService = $this->getServiceLocator()->get('wdgblog_service_blog');
        }
        return $this->blogService;
    }
}