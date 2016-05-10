<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class UserRepository extends EntityRepository
{
    /**
     * Find user by access token
     *
     * @param string $accessToken Access token
     *
     * @return User|null
     */
    public function findUserByAccessToken($accessToken)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->where($qb->expr()->eq('u.accessToken', ':access_token'))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->setParameter('access_token', $accessToken)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find user by refresh token
     *
     * @param string $refreshToken Refresh token
     *
     * @return User|null
     */
    public function findUserByRefreshToken($refreshToken)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->where($qb->expr()->eq('u.refreshToken', ':refresh_token'))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->setParameter('refresh_token', $refreshToken)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find friend users by user and status
     *
     * @param User   $user   User
     * @param string $status Friend status type
     *
     * @return User[]
     */
    public function findFriendUsersByUser(User $user, $status)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->where($qb->expr()->eq('fuu', ':user'))
                  ->andWhere($qb->expr()->eq('fu.status', ':status'))
                  ->andWhere($qb->expr()->eq('fuu.enabled', true))
                  ->andWhere($qb->expr()->eq('fuf.enabled', true))
                  ->join('u.friendUsers', 'fu')
                  ->join('fu.user', 'fuu')
                  ->join('fu.friend', 'fuf')
                  ->setParameters([
                      'user'   => $user,
                      'status' => $status,
                  ])
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find friend users by user and status with pagination
     *
     * @param User       $user      User
     * @param string     $status    Friend status type
     * @param Pagination $paginator Paginator
     *
     * @return User[]
     */
    public function findFriendUsersByUserWithPagination(User $user, $status, Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->where($qb->expr()->eq('fuu', ':user'))
                  ->andWhere($qb->expr()->eq('fu.status', ':status'))
                  ->andWhere($qb->expr()->eq('fuu.enabled', true))
                  ->andWhere($qb->expr()->eq('fuf.enabled', true))
                  ->join('u.friendUsers', 'fu')
                  ->join('fu.user', 'fuu')
                  ->join('fu.friend', 'fuf')
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->setParameters([
                      'user'   => $user,
                      'status' => $status,
                  ])
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find user by user and friend
     *
     * @param User $user   User
     * @param User $friend Friend
     *
     * @return User|null
     */
    public function findUserByUserFriend(User $user, User $friend)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->where($qb->expr()->eq('fuu', ':user'))
                  ->andWhere($qb->expr()->eq('fuf', ':friend'))
                  ->andWhere($qb->expr()->eq('fuu.enabled', true))
                  ->andWhere($qb->expr()->eq('fuf.enabled', true))
                  ->join('u.friendUsers', 'fu')
                  ->join('fu.user', 'fuu')
                  ->join('fu.friend', 'fuf')
                  ->setParameters([
                      'user'   => $user,
                      'friend' => $friend,
                  ])
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find friend status by user and friend
     *
     * @param User $user   User
     * @param User $friend Friend
     *
     * @return User|null
     */
    public function findFriendStatusByUserAndFriend(User $user, User $friend)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->select('fu.status')
                  ->where($qb->expr()->eq('fuu', ':user'))
                  ->andWhere($qb->expr()->eq('fuf', ':friend'))
                  ->join('u.friendUsers', 'fu')
                  ->join('fu.user', 'fuu')
                  ->join('fu.friend', 'fuf')
                  ->setParameters([
                      'user'   => $user,
                      'friend' => $friend,
                  ])
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
