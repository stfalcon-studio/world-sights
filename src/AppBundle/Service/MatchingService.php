<?php

namespace AppBundle\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Entity;

/**
 * MatchingService class
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class MatchingService
{
    /**
     * @var EntityManager $manager Entity manager
     */
    private $manager;

    /**
     * Constructor
     *
     * @param EntityManager $manager Entity manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Filter for select elements with criteria
     *
     * @param EntityRepository $repository Entity Repository
     * @param array            $fields     Fields
     * @param \Closure         $callback   Callback for add custom criteria
     *
     * @return Entity
     */
    public function matching(EntityRepository $repository, array $fields, \Closure $callback = null)
    {
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $this->manager->getClassMetadata($repository->getClassName());

        $fields = array_filter($fields, function ($value) {
            return !empty($value);
        });

        $sort   = null;
        $offset = null;
        $limit  = null;

        if (isset($fields['_sort'])) {
            $sort = $this->convertArrayStringUnderscoreToCamelCase($fields['_sort']);
            unset($fields['_sort']);
        }

        if (isset($fields['_offset'])) {
            $offset = $fields['_offset'];
            unset($fields['_offset']);
        }

        if (isset($fields['_limit'])) {
            $limit = $fields['_limit'];
            unset($fields['_limit']);
        }

        $fields = $this->convertArrayStringUnderscoreToCamelCase($fields);

        $criteria = new Criteria(null, $sort, $offset, $limit);
        $expr     = $criteria->expr();
        foreach ($fields as $field => $value) {
            if ($classMetadata->hasField($field)) {
                if (is_numeric($value)) {
                    $comparison = new Comparison($field, Comparison::EQ, new Value($value));
                } else {
                    $comparison = new Comparison($field, Comparison::CONTAINS, new Value($value));
                }

                $criteria->andWhere($comparison);
            } else {
                if ($classMetadata->hasAssociation($field)) {
                    $className = $classMetadata->getAssociationTargetClass($field);
                    $entity    = $this->manager->find($className, $value);

                    $criteria->andWhere($expr->eq($field, $entity));
                }
            }
        }

        if ($callback) {
            $callback($criteria);
        }

        return $repository->matching($criteria)->toArray();
    }

    /**
     * Convert array of underscore string to array of camel case string
     *
     * @param array $arrayStrings Array of strings
     *
     * @return array
     */
    private function convertArrayStringUnderscoreToCamelCase(array $arrayStrings)
    {
        $pattern = '/_([a-z])/';
        foreach ($arrayStrings as $key => $string) {
            if (preg_match($pattern, $key)) {
                $newKey                = $this->convertUnderscoreToCamelCase($key);
                $arrayStrings[$newKey] = $string;
                unset($arrayStrings[$key]);
            }
        }

        return $arrayStrings;
    }

    /**
     * Convert underscore string to camel case
     *
     * @param string $field Field to convert
     *
     * @return string
     */
    private function convertUnderscoreToCamelCase($field)
    {
        return preg_replace_callback('/_([a-z])/', function ($field) {
            return strtoupper($field[1]);
        }, $field);
    }
}
