<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTour;
use AppBundle\Form\Model\Pagination;
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
     * Pagination $paginator Paginator
     *
     * @return SightTour[]
     */
    public function findSightToursWithPagination(Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->getQuery()
                  ->getResult();
    }
}
