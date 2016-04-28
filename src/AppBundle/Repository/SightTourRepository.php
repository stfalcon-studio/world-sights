<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTour;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Tour Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightTourRepository extends EntityRepository
{
    /**
     * Find all enabled sight tours
     *
     * @return SightTour[]
     */
    public function findAllSightTours()
    {
        $qb = $this->createQueryBuilder('st');

        return $qb->where($qb->expr()->eq('st.enabled', true))
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight tours by sight
     *
     * @param Sight $sight Sight
     *
     * @return SightTour[]
     */
    public function findSightToursBySight(Sight $sight)
    {
        $qb = $this->createQueryBuilder('st');

        return $qb->where($qb->expr()->eq('s', ':sight'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->join('st.sight', 's')
                  ->setParameter('sight', $sight)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight tours with pagination
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function findSightToursWithPagination($limit = 10, $offset = 0)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->setFirstResult($offset)
                  ->setMaxResults($limit)
                  ->getQuery()
                  ->getResult();
    }
}
