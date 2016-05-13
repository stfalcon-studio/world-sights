<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SightPhoto;
use AppBundle\Form\Model\Pagination;
use Doctrine\ORM\EntityRepository;

/**
 * Sight Photo Repository
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightPhotoRepository extends EntityRepository
{
    /**
     * Find sight photos with pagination
     *
     * @param Pagination $pagination Pagination
     *
     * @return SightPhoto[]
     */
    public function findSightPhotosWithPagination(Pagination $pagination)
    {
        $qb = $this->createQueryBuilder('sp');

        return $qb->setFirstResult($pagination->getOffset())
                  ->setMaxResults($pagination->getLimit())
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get total number of enabled sight photos
     *
     * @return int
     */
    public function getTotalNumberOfEnabledSightPhotos()
    {
        $qb = $this->createQueryBuilder('sp');

        return (int) $qb->select('COUNT(sp)')
                        ->where($qb->expr()->eq('sp.enabled', true))
                        ->getQuery()
                        ->getSingleScalarResult();
    }
}
