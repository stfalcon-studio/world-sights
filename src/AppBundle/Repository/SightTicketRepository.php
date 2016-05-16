<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTicket;
use AppBundle\Form\Model\Pagination;
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

    /**
     * Find all enabled sight tickets
     *
     * @return SightTicket[]
     */
    public function findAllSightTickets()
    {
        $qb = $this->createQueryBuilder('st');

        return $qb->where($qb->expr()->eq('st.enabled', true))
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight tickets with pagination
     *
     * @param Pagination $pagination Pagination
     *
     * @return SightTicket[]
     */
    public function findSightTicketsWithPagination(Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('st');

        return $qb->where($qb->expr()->eq('st.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sight tickets
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightTickets()
    {
        $qb = $this->createQueryBuilder('st');

        return (int) $qb->select('COUNT(st)')
                        ->where($qb->expr()->eq('st.enabled', true))
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
