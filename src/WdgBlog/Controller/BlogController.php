<?php
namespace WdgBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    public function postAction()
    {
        $slug = $this->params()->fromRoute('slug', '');
        $service = $this->getServiceLocator()->get('wdgblog_service_blog');
        $post = $service->getPostBySlug($slug);
        
        if(!$post)
        {
            $this->flashMessenger ()->addErrorMessage ("Could not get post.");
            $this->redirect()->toRoute("home");
        }
        
        return new ViewModel(array("post" => $post));
    }
    
    public function categoryAction()
    {
        $slug = $this->params()->fromRoute('slug', '');
        $page = $this->params()->fromRoute('page', 1);
        $service = $this->getServiceLocator()->get('wdgblog_service_blog');
        
        $category = $service->getCategoryBySlug($slug);
        
        if(!$category)
        {
            $this->flashMessenger ()->addErrorMessage ("Could not get category.");
            $this->redirect()->toRoute("home");
        }
        
        $paginator = $service->getPostsByCategorySlugPaginator($slug, $page, 10);
        
        return new ViewModel(array("paginator" => $paginator, "category" => $category));
    }
}