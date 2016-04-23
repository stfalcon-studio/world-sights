<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
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
}
