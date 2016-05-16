<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightReview;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Review Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightReviewRepository extends EntityRepository
{
    /**
     * Find sight reviews with pagination
     *
     * @param Pagination $pagination Pagination
     *
     * @return SightReview[]
     */
    public function findSightReviewsWithPagination(Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('sr');

        return $qb->where($qb->expr()->eq('sr.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sight reviews by user
     *
     * @param User $user User
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightReviewsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('s');

        return (int) $qb->select('COUNT(s)')
                        ->where($qb->expr()->eq('s.user', ':user'))
                        ->andWhere($qb->expr()->eq('s.enabled', true))
                        ->setParameter('user', $user)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    /**
     * Get average mark by sight
     *
     * @param Sight $sight Sight
     *
     * @return float
     */
    public function getAverageMarkBySight(Sight $sight)
    {
        $qb = $this->createQueryBuilder('sr');

        return (float) $qb->select('AVG(sr.mark)')
                          ->where($qb->expr()->eq('s', ':sight'))
                          ->andWhere($qb->expr()->eq('sr.enabled', true))
                          ->join('sr.sight', 's')
                          ->setParameter('sight', $sight)
                          ->getQuery()
                          ->getSingleScalarResult();
    }

    /**
     * Find sight reviews by sight with pagination
     *
     * @param Sight      $sight      Sights
     * @param Pagination $pagination Pagination
     *
     * @return SightReview[]
     */
    public function findSightReviewsBySightWithPagination(Sight $sight, Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('sr');

        return $qb->where($qb->expr()->eq('s', ':sight'))
                  ->andWhere($qb->expr()->eq('sr.enabled', true))
                  ->join('sr.sight', 's')
                  ->setParameter('sight', $sight)
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find sight reviews by user with pagination
     *
     * @param User       $user       User
     * @param Pagination $pagination Pagination
     *
     * @return SightReview[]
     */
    public function findSightReviewsByUserWithPagination(User $user, Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('sr');

        return $qb->where($qb->expr()->eq('u', ':user'))
                  ->andWhere($qb->expr()->eq('sr.enabled', true))
                  ->join('sr.user', 'u')
                  ->setParameter('user', $user)
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sight reviews by sight
     *
     * @param Sight $sight Sight
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightReviewsBySight(Sight $sight)
    {
        $qb = $this->createQueryBuilder('s');

        return (int) $qb->select('COUNT(s)')
                        ->where($qb->expr()->eq('s.sight', ':sight'))
                        ->andWhere($qb->expr()->eq('s.enabled', true))
                        ->setParameter('sight', $sight)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    /**
     * Get total number of enabled sight reviews
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightReviews()
    {
        $qb = $this->createQueryBuilder('s');

        return (int) $qb->select('COUNT(s)')
                        ->where($qb->expr()->eq('s.enabled', true))
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
