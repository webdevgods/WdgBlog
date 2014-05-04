<?php
namespace WdgBlog\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection,
    WdgDoctrine2\Entity\Entity,
    ZfcUserDoctrineORM\Entity\User as UserEntity;

/**
 * Post
 *
 * @ORM\Table(
 *      name                = "wdgblog_posts",
 *      uniqueConstraints   = {
 *          @ORM\UniqueConstraint(name="slug_idx", columns={"slug"})
 *      },
 *      indexes = {
 *          @ORM\Index(name="title_idx",        columns={"title"}),
 *          @ORM\Index(name="creationDate_idx", columns={"created"}),
 *      }
 * )
 * @ORM\Entity(repositoryClass="WdgBlog\Repository\Post")
 */
class Post extends Entity
{

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $excerpt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;
    
    /**
     * @ORM\ManyToOne(targetEntity="FileBank\Entity\File")
     * @var \FileBank\Entity\File
     */
    protected $thumbnail;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="Posts")
     * @ORM\JoinTable(
     *  name="wdgblog_post_categories",
     *  joinColumns={
     *      @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $Categories;

    /**
     * @var \WdgUser\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="ZfcUserDoctrineORM\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="user_id", unique=false, nullable=false)
     */
    protected $Author;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->Categories   = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = (string)$body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $excerpt
     * @return \WdgBlog\Entity\Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = (string)$slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Set thumbnail
     *
     * @param \FileBank\Entity\File $thumbnail
     *
     * @return Post
     */
    public function setThumbnail(\FileBank\Entity\File $thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
    
    public function getThumbnailHtml($attributes = "style='width:100px'")
    {
        if($this->getThumbnail())
            return "<img src='".htmlspecialchars($this->getThumbnail()->getUrl()).
                    "' $attributes alt='".htmlspecialchars($this->getThumbnail()->getName())."' ".
                    " title='".htmlspecialchars($this->getThumbnail()->getName())."' />";
        
        return "";
    }

    /**
     * Add categories
     *
     * @param \Doctrine\Common\Collections\Collection|\WdgBlog\Entity\Category $categories
     *
     * @return Post
     */
    public function addCategories(Collection $categories)
    {
        foreach ($categories as $category) 
        {
            $this->addCategory($category);
        }

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Doctrine\Common\Collections\Collection|\WdgBlog\Entity\Category $categories
     *
     * @return \WdgBlog\Entity\Post
     */
    public function removeCategories(Collection $categories)
    {
        foreach ($categories as $category) 
        {
            $this->removeCategory($category);
        }

        return $this;
    }

    /**
     * @param \WdgBlog\Entity\Category $category
     */
    public function addCategory(Category $category)
    {
        if ($this->Categories->contains($category))return;

        $this->Categories->add($category);
        
        $category->addPost($this);
    }

    /**
     * @param \WdgBlog\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        if (!$this->Categories->contains($category)) return;

        $this->Categories->removeElement($category);
        
        $category->removePost($this);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->Categories;
    }

    /**
     * Set author
     *
     * @param \WdgUser\Entity\User $author
     *
     * @return Post
     */
    public function setAuthor(UserEntity $author = null)
    {
        $this->Author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \WdgUser\Entity\User
     */
    public function getAuthor()
    {
        return $this->Author;
    }

}
