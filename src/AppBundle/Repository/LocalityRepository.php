<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Locality;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Locality Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class LocalityRepository extends EntityRepository
{
    /**
     * Find locality first result
     *
     * @return Locality
     */
    public function findLocalityFirstResult()
    {
        $qb = $this->createQueryBuilder('l');

        return $qb->setMaxResults(1)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find all enabled localities
     *
     * @return Locality[]
     */
    public function findAllLocalities()
    {
        $qb = $this->createQueryBuilder('l');

        return $qb->where($qb->expr()->eq('l.enabled', true))
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find localities with pagination
     *
     * @param Pagination $pagination Pagination
     *
     * @return Locality[]
     */
    public function findLocalitiesWithPagination(Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('l');

        return $qb->where($qb->expr()->eq('l.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled localities
     *
     * @return int
     */
    public function getTotalNumberOfEnabledLocalities()
    {
        $qb = $this->createQueryBuilder('l');

        return (int) $qb->select('COUNT(l)')
                        ->where($qb->expr()->eq('l.enabled', true))
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
