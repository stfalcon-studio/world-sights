<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Country;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Country Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class CountryRepository extends EntityRepository
{
    /**
     * Find all enabled country
     *
     * @return Country[]
     */
    public function findAllCountries()
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where($qb->expr()->eq('c.enabled', true))
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find countries with pagination
     *
     * @param Pagination $pagination Pagination
     *
     * @return Country[]
     */
    public function findCountriesWithPagination(Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where($qb->expr()->eq('c.enabled', true))
                  ->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled countries
     *
     * @return int
     */
    public function getTotalNumberOfEnabledCountries()
    {
        $qb = $this->createQueryBuilder('c');

        return (int) $qb->select('COUNT(c)')
                        ->where($qb->expr()->eq('c.enabled', true))
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
