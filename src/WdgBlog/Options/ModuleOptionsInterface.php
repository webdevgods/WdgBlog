<?php

namespace WdgBlog\Options;

interface ModuleOptionsInterface
{
    public function setPostListElements(array $listElements);

    public function getPostListElements();
    
    public function setThumbnailImageTag($thumbnailImageTag);
    
    /**
     * Filebank tag for blog thumbnail specific images
     */
    public function getThumbnailImageTag();
}
