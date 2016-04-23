<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTicket;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Ticket Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightTicketRepository extends EntityRepository
{
    /**
     * Find sight tickets by sight
     *
     * @param Sight $sight Sight
     *
     * @return SightTicket[]
     */
    public function findSightTicketsBySight(Sight $sight)
    {
        $qb = $this->createQueryBuilder('st');

        return $qb->where($qb->expr()->eq('s', ':sight'))
                  ->andWhere($qb->expr()->eq('st.enabled', true))
                  ->join('st.sight', 's')
                  ->setParameter('sight', $sight)
                  ->getQuery()
                  ->getResult();
    }
}
