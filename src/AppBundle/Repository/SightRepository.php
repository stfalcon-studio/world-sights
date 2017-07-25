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
     * @param Pagination $pagination Pagination
     *
     * @return Sight[]
     */
    public function findSightsWithPagination(Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('s.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
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
     * @param User       $user       User
     * @param Pagination $pagination Pagination
     *
     * @return SightVisit[]
     */
    public function findSightBySightVisitUserWithPagination(User $user, Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->where($qb->expr()->eq('u', ':user'))
                  ->andWhere($qb->expr()->eq('s.enabled', true))
                  ->andWhere($qb->expr()->eq('u.enabled', true))
                  ->join('s.sightVisits', 'sv')
                  ->join('sv.user', 'u')
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
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
                  ->orderBy('sv.date', 'DESC')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight by sight visit by friend with pagination
     *
     * @param User       $user       User
     * @param Pagination $pagination Pagination
     *
     * @return array
     */
    public function findSightBySightVisitByFriendsWithPagination(User $user, Pagination $pagination)
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
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->orderBy('sv.date', 'DESC')
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sights
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSights()
    {
        $qb = $this->createQueryBuilder('s');

        return (int) $qb->select('COUNT(s)')
                        ->where($qb->expr()->eq('s.enabled', true))
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    /**
     * Get total number of enabled sights
     *
     * @param User $user User
     *
     * @return int
     */
    public function getTotalNumberOfVisitedSightsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('s');

        return (int) $qb->select('COUNT(s)')
                        ->where($qb->expr()->eq('u', ':user'))
                        ->andWhere($qb->expr()->eq('s.enabled', true))
                        ->join('s.sightVisits', 'sv')
                        ->join('sv.user', 'u')
                        ->setParameter('user', $user)
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
