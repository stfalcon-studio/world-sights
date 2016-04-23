<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Sight Type Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightTypeRepository extends EntityRepository
{
    /**
     * Find sight type first result
     *
     * @return Sight
     */
    public function findSightTypeFirstResult()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->setMaxResults(1)
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
