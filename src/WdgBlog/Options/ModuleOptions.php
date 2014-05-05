<?php
namespace WdgBlog\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements ModuleOptionsInterface
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * Array of data to show in the post list
     * Key = Label in the list
     * Value = entity property(expecting a 'getProperty())
     */
    protected $postListElements = array('Title' => 'title', 'Slug' => 'slug');
    
    /**
     * Array of data to show in the category list
     * Key = Label in the list
     * Value = entity property(expecting a 'getProperty())
     */
    protected $categoryListElements = array('Name' => 'name', 'Slug' => 'slug');
    
    /**
     * @var Filebank tag for blog thumbnail specific images
     */
    protected $thumbnailImageTag = "";

    public function setPostListElements(array $listElements)
    {
        $this->postListElements = $listElements;
    }

    public function getPostListElements()
    {
        return $this->postListElements;
    }
    
    public function setCategoryListElements(array $listElements)
    {
        $this->categoryListElements = $listElements;
    }

    public function getCategoryListElements()
    {
        return $this->categoryListElements;
    }
    
    /**
     * This is the name of the tag to put in the filebank for all of the
     * thumbnail images so they can be filtered later
     * 
     * @param string $thumbnailImageTag
     */
    public function setThumbnailImageTag($thumbnailImageTag)
    {
        $this->thumbnailImageTag = $thumbnailImageTag;
    }
    
    /**
     * Filebank tag for blog thumbnail specific images
     * 
     * @return type
     */
    public function getThumbnailImageTag()
    {
        return $this->thumbnailImageTag;
    }
}
