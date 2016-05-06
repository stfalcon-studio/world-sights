<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Friend Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class FriendRepository extends EntityRepository
{
    /**
     * Find friend by user-user and user-friend
     *
     * @param User $user   User
     * @param User $friend Friend
     *
     * @return User|null
     */
    public function findFriendByUserFriend(User $user, User $friend)
    {
        $qb = $this->createQueryBuilder('f');

        return $qb->where($qb->expr()->eq('fu', ':user'))
                  ->andWhere($qb->expr()->eq('ff', ':friend'))
                  ->andWhere($qb->expr()->eq('fu.enabled', true))
                  ->andWhere($qb->expr()->eq('ff.enabled', true))
                  ->join('f.user', 'fu')
                  ->join('f.friend', 'ff')
                  ->setParameters([
                      'user'   => $user,
                      'friend' => $friend,
                  ])
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
