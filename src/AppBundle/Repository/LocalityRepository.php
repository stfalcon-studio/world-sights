<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Locality;
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
}
