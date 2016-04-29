<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightRepository extends EntityRepository
{
    /**
     * Find all enabled sights
     *
     * @return Sight[]
     */
    public function findAllSights()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight by slug
     *
     * @param string $slug Slug
     *
     * @return Sight|null
     */
    public function findSightBySlug($slug)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.slug', ':slug'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->setParameter('slug', $slug)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find sights with pagination
     *
     * Pagination $paginator Paginator
     *
     * @return Sight[]
     */
    public function findSightsWithPagination(Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight first result
     *
     * @return Sight|null
     */
    public function findSightFirstResult()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->setMaxResults(1)
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
