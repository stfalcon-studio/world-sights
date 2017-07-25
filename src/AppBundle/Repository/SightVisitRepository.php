<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Visit Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightVisitRepository extends EntityRepository
{
    /**
     * Find sight visit by sight and user
     *
     * @param Sight $sight Sight
     * @param User  $user User
     *
     * @return SightVisit|null
     */
    public function findSightVisitBySightAndUser(Sight $sight, User $user)
    {
        $qb = $this->createQueryBuilder('sv');

        return $qb->where($qb->expr()->eq('s', ':sight'))
                  ->andWhere($qb->expr()->eq('u', ':user'))
                  ->join('sv.sight', 's')
                  ->join('sv.user', 'u')
                  ->setParameters([
                      'sight' => $sight,
                      'user'  => $user,
                  ])
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
