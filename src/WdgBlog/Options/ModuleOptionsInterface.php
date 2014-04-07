<?php

namespace WdgBlog\Options;

interface ModuleOptionsInterface
{
    public function setPostListElements(array $listElements);

    public function getPostListElements();
}
