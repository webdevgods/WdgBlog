<?php
namespace WdgBlog\Service;

use Zend\Form\Form,
    WdgZf2\Service\ServiceAbstract,
    DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter,
    Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator,
    Zend\Paginator\Paginator as ZendPaginator,
    WdgBlog\Entity\Post as PostEntity,
    WdgBlog\Entity\Category as CategoryEntity,
    WdgBlog\Exception\Service\Blog\FormException as FormException,
    WdgBlog\Options\ModuleOptionsInterface;

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
     * @var \WdgBlog\Options\ModuleOptionsInterface
     */
    protected $options;


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
        $User   = $this->getUserService()->getUserMapper()->findById($data["author_id"]);
        
        if(!$User)throw new FormException("Form values are invalid. Incorrect User");
        
        $Post   = new PostEntity();
        
        $em->beginTransaction();
        
        $file = null;
        
        if(isset($data["thumbnail"]) && is_array($data["thumbnail"]) )
        {
            $tags = array();
            
            if($this->getOptions()->getThumbnailImageTag())
            {
                $tags[] = $this->getOptions()->getThumbnailImageTag();
            }
            
            /* @var $fileBank \FileBank\Manager */
            $fileBank = $this->getServiceManager()->get('FileBank');

            /* @var $file \FileBank\Entity\File */
            $file = $fileBank->save($data["thumbnail"]["tmp_name"], $tags);
            
            if(isset($data["thumbnail_name"]) && strlen($data["thumbnail_name"]) > 0)
                $file->setName($data["thumbnail_name"]);
        }
        
        $Post->setTitle($data["title"])
            ->setSlug($data["slug"])
            ->setExcerpt($data["excerpt"])
            ->setBody($data["body"])
            ->setAuthor($User);
        
        if($file != null)
        {
            $Post->setThumbnail($file);
        
            $em->persist($file);
        }
        
        $em->persist($Post);  
        
        $em->commit(); 
              
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
        $User   = $this->getUserService()->getUserMapper()->findById($data["author_id"]);
        
        if(!$User)throw new FormException("Form values are invalid. Incorrect User");
        
        $Post = $this->getPostById($data["id"]);
        
        $em->beginTransaction();
        
        $file = null;
        
        if(isset($data["thumbnail"]) && is_array($data["thumbnail"]) )
        {
            $tags = array();
            
            if($this->getOptions()->getThumbnailImageTag())
            {
                $tags[] = $this->getOptions()->getThumbnailImageTag();
            }
            
            /* @var $fileBank \FileBank\Manager */
            $fileBank = $this->getServiceManager()->get('FileBank');

            /* @var $file \FileBank\Entity\File */
            $file = $fileBank->save($data["thumbnail"]["tmp_name"], $tags);
            
            if(isset($data["thumbnail_name"]) && strlen($data["thumbnail_name"]) > 0)
                $file->setName($data["thumbnail_name"]);
        }
        
        $Post->setTitle($data["title"])
            ->setSlug($data["slug"])
            ->setExcerpt($data["excerpt"])
            ->setBody($data["body"])
            ->setAuthor($User);
        
        if($file != null)
        {
            $Post->setThumbnail($file);
        
            $em->persist($file);
        }
        
        $em->persist($Post);  
        
        $em->commit();
              
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
     * @param int $id
     * @return \WdgBlog\Service\Blog
     * @throws \Exception
     */
    public function deletePost($id)
    {
        $post = $this->getPostById($id);
        
        if(!$post)
            throw new \Exception("Could not delete post. That post does not exist.");
        
        $em = $this->getEntityManager();
        
        $em->remove($post);
        $em->flush();
        
        return $this;
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
     * @param int $id
     * @return \WdgBlog\Service\Blog
     * @throws \Exception
     */
    public function deleteCategory($id)
    {
        $category = $this->getCategoryById($id);
        
        if(!$category)
            throw new \Exception("Could not delete category. That category does not exist.");
        
        $em = $this->getEntityManager();
        
        $em->remove($category);
        $em->flush();
        
        return $this;
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
            $this->userService = $this->getServiceManager()->get("zfcuseradmin_user_service");
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
    
    /**
     * @param \WdgBlog\Service\ModuleOptionsInterface $options
     */
    public function setOptions( ModuleOptionsInterface $options )
    {
        $this->options = $options;
    }

    /**
     * @return \WdgBlog\Options\ModuleOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('wdgblog_module_options'));
        }
        
        return $this->options;
    }
}