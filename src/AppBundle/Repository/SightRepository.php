<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightRepository extends EntityRepository
{
    /**
     * Find all enabled sights
     *
     * @return Sight[]
     */
    public function findAllSights()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight by slug
     *
     * @param string $slug Slug
     *
     * @return Sight|null
     */
    public function findSightBySlug($slug)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.slug', ':slug'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->setParameter('slug', $slug)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find sights with pagination
     *
     * Pagination $paginator Paginator
     *
     * @return Sight[]
     */
    public function findSightsWithPagination(Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight first result
     *
     * @return Sight|null
     */
    public function findSightFirstResult()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->setMaxResults(1)
                  ->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Find sight by visited sight by user
     *
     * @param User $user User
     *
     * @return SightVisit[]
     */
    public function findSightBySightVisitUser(User $user)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('u', ':user'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->join('s.sightVisits', 'sv')
                  ->join('sv.user', 'u')
                  ->orderBy('s.id', 'DESC')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight by visited sight by user with pagination
     *
     * @param User $user User
     *
     * @return SightVisit[]
     */
    public function findSightBySightVisitUserWithPagination(User $user, Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('u', ':user'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->join('s.sightVisits', 'sv')
                  ->join('sv.user', 'u')
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->orderBy('s.id', 'DESC')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight by sight visit by friend
     *
     * @param User $user User
     *
     * @return array
     */
    public function findSightBySightVisitByFriends(User $user)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->addSelect('IDENTITY(uf.friend) as user')
                  ->where($qb->expr()->eq('uff', ':user'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->join('s.sightVisits', 'sv')
                  ->join('sv.user', 'u')
                  ->join('u.userFriends', 'uf')
                  ->join('uf.friend', 'uff')
                  ->orderBy('s.id', 'DESC')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight by sight visit by friend with pagination
     *
     * @param User $user User
     *
     * @return array
     */
    public function findSightBySightVisitByFriendsWithPagination(User $user, Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->addSelect('IDENTITY(uf.friend) as user')
                  ->where($qb->expr()->eq('uff', ':user'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->join('s.sightVisits', 'sv')
                  ->join('sv.user', 'u')
                  ->join('u.userFriends', 'uf')
                  ->join('uf.friend', 'uff')
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->orderBy('s.id', 'DESC')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }
}
