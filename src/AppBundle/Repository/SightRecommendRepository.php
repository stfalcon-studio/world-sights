<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightRecommend;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Recommend Repository
 *
 * @author Yevgeniy Zholkevskiy <zhenya.zholkevskiy@gmail.com>
 */
class SightRecommendRepository extends EntityRepository
{
    /**
     * Find sight recommends with pagination by user
     *
     * @param User       $user       User
     * @param Pagination $pagination Pagination
     *
     * @return SightRecommend[]
     */
    public function findSightRecommendsByUserWithPagination(User $user, Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('sr');

        return $qb->where($qb->expr()->eq('sr.user', ':user'))
                  ->andWhere($qb->expr()->eq('sr.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->setParameter('user', $user)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sight recommends by user
     *
     * @param User $user User
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightRecommendsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('sp');

        return (int) $qb->select('COUNT(sp)')
                        ->where($qb->expr()->eq('sp.user', ':user'))
                        ->andWhere($qb->expr()->eq('sp.enabled', true))
                        ->setParameter('user', $user)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    /**
     * Find sight recommends with pagination by sight
     *
     * @param Sight      $sight      Sight
     * @param Pagination $pagination Pagination
     *
     * @return SightRecommend[]
     */
    public function findSightRecommendsBySightWithPagination(Sight $sight, Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('sr');

        return $qb->where($qb->expr()->eq('sr.sight', ':sight'))
                  ->andWhere($qb->expr()->eq('sr.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->setParameter('sight', $sight)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sight recommends by sight
     *
     * @param Sight $sight Sight
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightRecommendsBySight(Sight $sight)
    {
        $qb = $this->createQueryBuilder('sp');

        return (int) $qb->select('COUNT(sp)')
                        ->where($qb->expr()->eq('sp.sight', ':sight'))
                        ->andWhere($qb->expr()->eq('sp.enabled', true))
                        ->setParameter('sight', $sight)
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
