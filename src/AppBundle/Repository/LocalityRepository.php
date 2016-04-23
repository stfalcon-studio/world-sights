<?php

namespace AppBundle\Repository;

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
     * @return Sight
     */
    public function findLocalityFirstResult()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->setMaxResults(1)
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
