<?php
namespace WdgBlog\Repository;

use Doctrine\ORM\EntityRepository;

class Category extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function findAlphaPaginationQuery()
    {
        return $this->createQueryBuilder("p")
                ->select("p")
                ->orderBy("p.name", "DESC")
                ->getQuery();
    }
}