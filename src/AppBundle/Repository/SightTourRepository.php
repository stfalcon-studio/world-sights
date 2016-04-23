<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightType;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Tour Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightTourRepository extends EntityRepository
{
    /**
     * Find sight tour by sight
     *
     * @param Sight $sight Sight
     *
     * @return SightType[]
     */
    public function findSightTourBySight(Sight $sight)
    {
        $qb = $this->createQueryBuilder('st');

        return $qb->where($qb->expr()->eq('s', ':sight'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->join('st.sight', 's')
                  ->setParameter('sight', $sight)
                  ->getQuery()
                  ->getResult();
    }
}
