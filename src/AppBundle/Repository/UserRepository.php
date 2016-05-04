<?php

namespace AppBundle\Repository;

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
}
