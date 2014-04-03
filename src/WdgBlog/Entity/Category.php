<?php
namespace WdgBlog\Entity;

use Doctrine\ORM\Mapping as ORM, 
    WdgDoctrine2\Entity\Entity,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(
 *      name="wdgblog_categories",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="name_idx", columns={"name"}),
 *          @ORM\UniqueConstraint(name="slug_idx", columns={"slug"})
 *      },
 *      indexes={
 *          @ORM\Index(name="creationDate_idx", columns={"created"})
 *      }
 * )
 * @ORM\Entity(repositoryClass="WdgBlog\Repository\Category")
 */
class Category extends Entity
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
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="Categories")
     */
    protected $Posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->Posts = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

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
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->Posts;
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        if ($this->Posts->contains($post)) return;

        $this->Posts->add($post);
        
        $post->addCategory($this);
    }

    /**
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        if (!$this->Posts->contains($post)) return;

        $this->Posts->removeElement($post);
        
        $post->removeCategory($this);
    }

}
