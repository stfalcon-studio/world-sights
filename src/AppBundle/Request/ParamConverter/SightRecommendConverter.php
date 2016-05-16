<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Sight Recommend Converter
 *
 * @author Yevgeniy Zholkevskiy <zhenya.zholkevskiy@gmail.com>
 */
class SightRecommendConverter implements ParamConverterInterface
{
    /**
     * @var  EntityManager $em Entity manager
     */
    private $em;

    /**
     * @var TokenStorageInterface $tokenStorage Token storage
     */
    private $tokenStorage;

    /**
     * Constructor
     *
     * @param EntityManager         $em           Entity manager
     * @param TokenStorageInterface $tokenStorage Token storage
     */
    public function __construct(EntityManager $em, TokenStorageInterface $tokenStorage)
    {
        $this->em           = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        /** @var User $user */
        $user   = $this->tokenStorage->getToken()->getUser();
        $userID = $user->getId();

        $request->request->set('user', $userID);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        $className = $this->em->getClassMetadata($configuration->getClass())->getName();

        if ('AppBundle\Entity\SightRecommend' === $className) {
            return true;
        } else {
            return false;
        }
    }
}
