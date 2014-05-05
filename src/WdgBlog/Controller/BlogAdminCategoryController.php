<?php
namespace WdgBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    WdgBlog\Options\ModuleOptions;

class BlogAdminCategoryController extends AbstractActionController
{
    protected $options;
    protected $blogService;
    
    public function listAction()
    {
        $page       = (int) $this->params()->fromRoute('page', 0);
        $paginator  = $this->getBlogService()->getCategoriesPaginator($page, 10);
        
        if($paginator->count() > 0 && $paginator->count() < $page)
            $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/category/list");
        
        return new ViewModel(array(
            'categories' => $paginator,
            'categorylistElements' => $this->getOptions()->getCategoryListElements()
        ));
    }
    
    public function showAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        return new ViewModel(array("category" => $this->getBlogService()->getCategoryById($id)));
    }
    
    public function addAction()
    {
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

                return $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/category/show", array("id" => $Category->getId()));
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

                return $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/category/show", array("id" => $Category->getId()));
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
    
    public function deleteAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam("id");
        
        try 
        {
            $this->getBlogService()->deleteCategory($id);
            
            $this->flashMessenger()->addSuccessMessage("Category Deleted");
        } 
        catch(\Exception $exc) 
        {
            $this->flashMessenger()->addErrorMessage($exc->getMessage());
        }
        
        return $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/category/list");
    }
    
    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('wdgblog_module_options'));
        }
        return $this->options;
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