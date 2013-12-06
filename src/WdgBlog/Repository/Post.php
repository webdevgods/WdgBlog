<?php
namespace WdgBlog\Repository;

use Doctrine\ORM\EntityRepository;

class Post extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function findByLatestPostPaginationQuery()
    {
        return $this->createQueryBuilder("p")
                ->select("p")
                ->orderBy("p.created", "DESC")
                ->getQuery();
    }
    
    public function findPostsByCategorySlugPaginationQuery($slug)
    {
        $qb	= $this->createQueryBuilder("p");
        
        return $qb->select("p")
                ->leftJoin("p.Categories", "c")
                ->where($qb->expr()->like('c.slug', ':s'))
                ->orderBy("p.created", "DESC")
                ->setParameter("s", $slug)
                ->getQuery();
    }
}