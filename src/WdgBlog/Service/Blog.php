<?php
namespace WdgBlog\Service;

use Zend\Form\Form,
    WdgBase\Service\ServiceAbstract,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator as ZendPaginator,
    WdgBlog\Entity\Post as PostEntity,
    WdgBlog\Entity\Category as CategoryEntity,
    WdgBlog\Exception\Service\Blog\FormException as FormException;

class Blog extends ServiceAbstract
{
    /**
     * @var \WdgBlog\Repository\Post
     */
    protected $postRepos;
    
    /**
     * @var \WdgBlog\Repository\Category
     */
    protected $categoryRepos;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /**
     * @var \WdgUser\Service\User 
     */
    protected $userService;
    
    /**
     * @param string $slug
     * @return PostEntity
     */
    public function getPostBySlug($slug)
    {
        return $this->getPostRepository()->findOneBy(array("slug" => $slug));
    }
    
    /**
     * @param int $id
     * @return PostEntity
     */
    public function getPostById($id)
    {
        return $this->getPostRepository()->findOneBy(array("id" => $id));
    }
    
    public function getCategoryById($id)
    {
        return $this->getCategoryRepository()->findOneBy(array("id" => $id));
    }
    
    public function getCategoryBySlug($slug)
    {
        return $this->getCategoryRepository()->findOneBy(array("slug" => $slug));
    }
    
    /**
     * @param int $pageNumber
     * @param int $postsPerPage
     * @return ZendPaginator
     */
    public function getLatestPostsPaginator($pageNumber, $postsPerPage)
    {
        $paginator = new ZendPaginator(
                        new PaginatorAdapter(
                            new ORMPaginator($this->getPostRepository()->findByLatestPostPaginationQuery())
                        )
                    );
        
        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($postsPerPage);
    }
    
    /**
     * @param int $pageNumber
     * @param int $categoriesPerPage
     * @return ZendPaginator
     */
    public function getCategoriesPaginator($pageNumber, $categoriesPerPage)
    {
        $paginator = new ZendPaginator(
                        new PaginatorAdapter(
                            new ORMPaginator($this->getCategoryRepository()->findAlphaPaginationQuery())
                        )
                    );
        
        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($categoriesPerPage);
    }
    
    /**
     * @param string $slug
     * @param int $pageNumber
     * @param int $postsPerPage
     * @return ZendPaginator
     */
    public function getPostsByCategorySlugPaginator($slug, $pageNumber, $postsPerPage)
    {
        $paginator = new ZendPaginator(
                        new PaginatorAdapter(
                            new ORMPaginator(
                                $this->getPostRepository()
                                    ->findPostsByCategorySlugPaginationQuery($slug)
                            )
                        )
                    );
        
        return $paginator->setCurrentPageNumber($pageNumber)->setItemCountPerPage($postsPerPage);
    }
    
    /**
     * @return array
     */
    public function getAllCategories()
    {
        return $this->getCategoryRepository()->findBy(array(), array('name' => 'ASC'));
    }
    
    /**
     * @param array $array
     * @return \WdgBlog\Entity\Post
     * @throws FormException
     */
    public function addPostByArray(array $array)
    {
        $form   = $this->getAddPostForm();
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())throw new FormException("Form values are invalid");
        
        $data   = $form->getInputFilter()->getValues();
        $User   = $this->getUserService()->get($data["author_id"]);
        
        if(!$User)throw new FormException("Form values are invalid. Incorrect User");
        
        $Post   = new PostEntity();
        
        $Post->setTitle($data["title"])
            ->setSlug($data["slug"])
            ->setThumbnail($data["thumbnail"])
            ->setThumbnailAlt($data["thumbnail_alt"])
            ->setExcerpt($data["excerpt"])
            ->setBody($data["body"])
            ->setAuthor($User);
        
        $em->persist($Post);  
              
        $em->flush();
        
        return $Post;
    }
    
    /**
     * @param array $array
     * @return CategoryEntity
     * @throws FormException
     */
    public function editPostByArray($array)
    {
        $form   = $this->getEditPostForm($array["id"]);
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())throw new FormException("Form values are invalid");
        
        $data   = $form->getInputFilter()->getValues();
        $User   = $this->getUserService()->get($data["author_id"]);
        
        if(!$User)throw new FormException("Form values are invalid. Incorrect User");
        
        $Post = $this->getPostById($data["id"]);
        
        $Post->setTitle($data["title"])
            ->setSlug($data["slug"])
            ->setThumbnail($data["thumbnail"])
            ->setThumbnailAlt($data["thumbnail_alt"])
            ->setExcerpt($data["excerpt"])
            ->setBody($data["body"])
            ->setAuthor($User);
        
        $em->persist($Post);  
              
        $em->flush();
        
        return $Post;
    }
    
    public function editPostCategoriesByArray($array)
    {
        $form   = $this->getPostCategoryForm($array["id"]);
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())
            throw new FormException("Form values are invalid");
       
        $data = $form->getInputFilter()->getValues();
        
        $Post = $this->getPostById($data["id"]);
        
        if(!$Post)throw new FormException("Form values are invalid. Incorrect Post");
        
        $Post->getCategories()->clear();
        
        if(is_array($data["categories"]))
            foreach($data["categories"] as $category_id)
            {
                $Category = $this->getCategoryById($category_id);

                if(!$Category)throw new FormException("Form values are invalid. Incorrect Category");

                $Post->addCategory($Category);
            }
        
        $em->persist($Post);  
              
        $em->flush();
        
        return $Post;
    }
    
    /**
     * @param array $array
     * @return CategoryEntity
     * @throws FormException
     */
    public function editCategoryByArray($array)
    {
        $form   = $this->getEditCategoryForm($array["id"]);
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())throw new FormException("Form values are invalid");
        
        $data   = $form->getInputFilter()->getValues();
        
        $Category = $this->getCategoryById($data["id"]);
        
        $Category->setName($data["name"])
            ->setSlug($data["slug"]);
        
        $em->persist($Category);  
              
        $em->flush();
        
        return $Category;
    }
    
    /**
     * @param array $array
     * @return \WdgBlog\Entity\Category
     * @throws FormException
     */
    public function addCategoryByArray(array $array)
    {
        $form   = $this->getAddCategoryForm();
        $em     = $this->getEntityManager();
        
        $form->setData($array);
        
        if(!$form->isValid())throw new FormException("Form values are invalid");
        
        $data       = $form->getInputFilter()->getValues();
        $Category   = new CategoryEntity();
        
        $Category->setName($data["name"])
            ->setSlug($data["slug"]);
        
        $em->persist($Category);  
              
        $em->flush();
        
        return $Category;
    }
    
    /**
     * @return Form
     */
    public function getAddPostForm()
    {
        return $this->getServiceManager()->get('FormElementManager')->get('wdgblog_post_add_form');
    }
    
    /**
     * @return Form
     */
    public function getAddCategoryForm()
    {
        return $this->getServiceManager()->get('FormElementManager')->get('wdgblog_category_add_form');
    }
    
    /**
     * @return Form
     */
    public function getEditPostForm($id)
    {
        $Post = $this->getPostById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgblog_post_edit_form');
        
        $form->populateValues($Post->toArray());
        
        return $form;
    }
    
    /**
     * @return Form
     */
    public function getEditCategoryForm($id)
    {
        $Category = $this->getCategoryById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgblog_category_edit_form');
        
        $form->populateValues($Category->toArray());
        
        return $form;
    }
    
    /**
     * @return Form
     */
    public function getPostCategoryForm($id)
    {
        $Post = $this->getPostById($id);
        /* @var $form \Zend\Form\Form */
        $form = $this->getServiceManager()->get('FormElementManager')->get('wdgblog_post_category_form');
        
        $values = array("id" => $Post->getId());
        
        if($form->get("categories") instanceof \Zend\Form\Element\MultiCheckbox)
        {
            $category_values = array();
            
            foreach($Post->getCategories() as $Category)
                $category_values[] = $Category->getId();
            
            $values["categories"] = $category_values;
        }
       
        $form->populateValues($values);
        
        return $form;
    }
    
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getPostRepository()
    {
        if (null === $this->postRepos)
        {
            $this->postRepos = $this->getServiceManager()->get('wdgblog_repos_post');
        }
        
        return $this->postRepos;
    }
    
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getCategoryRepository()
    {
        if (null === $this->categoryRepos)
        {
            $this->categoryRepos = $this->getServiceManager()->get('wdgblog_repos_category');
        }
        
        return $this->categoryRepos;
    }
    
    /**
     * @return \WdgUser\Service\User
     */
    protected function getUserService()
    {
        if($this->userService === null)
        {
            $this->userService = $this->getServiceManager()->get("wdguser_service_user");
        }
        
        return $this->userService;
    }
    
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if($this->entityManager === null)
        {
            $this->entityManager = $this->getServiceManager()->get("wdgblog_doctrine_em");
        }
        
        return $this->entityManager;
    }
}