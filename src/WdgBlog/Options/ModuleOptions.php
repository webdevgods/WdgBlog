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

    public function setPostListElements(array $listElements)
    {
        $this->postListElements = $listElements;
    }

    public function getPostListElements()
    {
        return $this->postListElements;
    }
}
