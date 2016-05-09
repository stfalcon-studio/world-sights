<?php

namespace AppBundle\EntityListener;

use AppBundle\Entity\SightTicket;
use AppBundle\Service\SlugService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Slug Listener
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SlugListener
{
    /**
     * @var SlugService $slugify Slugify
     */
    private $slugify;

    /**
     * Constructor
     *
     * @param SlugService $slugify Slugify
     */
    public function __construct(SlugService $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * Pre persist
     *
     * @param LifecycleEventArgs $args Arguments
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->createSlug($args);
    }

    /**
     * Pre update
     *
     * @param LifecycleEventArgs $args Arguments
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->createSlug($args);
    }

    /**
     * Create slug
     *
     * @param LifecycleEventArgs $args
     */
    private function createSlug(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (method_exists($entity, 'setSlug')) {
            if ($entity instanceof SightTicket) {
                $slug = $this->slugify->createSlugSightTicket($entity);
            } else {
                $slug = $this->slugify->createSlug($entity->getName());
            }

            $entity->setSlug($slug);
        }
    }
}
