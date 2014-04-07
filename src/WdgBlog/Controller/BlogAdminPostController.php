<?php
namespace WdgBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    WdgBlog\Options\ModuleOptions;

class BlogAdminPostController extends AbstractActionController
{
    protected $options;
    protected $blogService;
    
    public function listAction()
    {        
        $page       = (int) $this->params()->fromRoute('page', 0);
        $paginator  = $this->getBlogService()->getLatestPostsPaginator($page, 10);
        
        if($paginator->count() >0 && $paginator->count() < $page)
            $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/post/list");
        
        return new ViewModel(array(
            'posts' => $paginator,
            'postlistElements' => $this->getOptions()->getPostListElements()
        ));
    }
    
    public function showAction()
    {        
        $id     = (int) $this->params()->fromRoute('id', 0);
        $post   = $this->getBlogService()->getPostById($id);
        
        if(!$post)
        {
            $this->flashMessenger()
                ->addErrorMessage(
                    $this->getTranslator()->translate('No blog post with that id')
                );
            
            $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/post/list");
        }
        
        return new ViewModel(array("post" => $this->getBlogService()->getPostById($id)));
    }
    
    public function addAction()
    {        
        $this->getServiceLocator()
            ->get('viewhelpermanager')
            ->get('HeadScript')
            ->prependFile('http://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.0.1/ckeditor.js');
        
        $service    = $this->getBlogService();
        $form       = $service->getAddPostForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Post = $service->addPostByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Added Post");

                return $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/post/show", array("id" => $Post->getId()));
            }
            catch (\WdgBlog\Exception\Service\Blog\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not add post: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function editAction()
    {        
        $this->getServiceLocator()
            ->get('viewhelpermanager')
            ->get('HeadScript')
            ->prependFile('/ckeditor/ckeditor.js');
        
        $service    = $this->getBlogService();
        $form       = $service->getEditPostForm($this->getEvent()->getRouteMatch()->getParam("id"));
        $request    = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Post = $service->EditPostByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Edited Post");

                return $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/post/show", array("id" => $Post->getId()));
            }
            catch (\WdgBlog\Exception\Service\Blog\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not edit post: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function categoriesAction()
    {        
        $service    = $this->getBlogService();
        $id         = $this->getEvent()->getRouteMatch()->getParam("id");
        $form       = $service->getPostCategoryForm($id);
        $request    = $this->getRequest();
        $Post       = $service->getPostById($id);
                
        if($request->isPost())
        {
            $post = $request->getPost();
            
            try 
            {
                $Post = $service->editPostCategoriesByArray($post->toArray());
                
                $this->flashMessenger()->addSuccessMessage("Post Categories Saved");

                return $this->redirect()->toRoute("zfcadmin/wdg-blog-admin/post/show", array("id" => $Post->getId()));
            }
            catch (\WdgBlog\Exception\Service\Blog\FormException $exc)
            {
                $this->flashMessenger()->addErrorMessage($exc->getMessage());
            }
            catch (\Exception $exc)
            {
                $this->flashMessenger()->addErrorMessage("Could not manage post categories: ".$exc->getMessage());
            }
            
            $form->setData($post)->isValid();   
        }
        
        return new ViewModel(array("form" => $form, "post" => $Post));
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
    
    public function getTranslator()
    {
        return $this->getServiceLocator()->get('translator');
    }
}