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
     * @param Pagination $paginator Pagination
     *
     * @return Country[]
     */
    public function findCountriesWithPagination(Pagination $paginator)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where($qb->expr()->eq('c.enabled', true))
                  ->setFirstResult($paginator->getOffset())
                  ->setMaxResults($paginator->getLimit())
                  ->getQuery()
                  ->getResult();
    }
}
