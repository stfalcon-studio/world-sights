<?php

namespace AppBundle\Service;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Translator;

/**
 * SlugService
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SlugService
{
    /**
     * @var EntityManager $manager Entity manager
     */
    private $manager;

    /**
     * @var Slugify $slugify Slugify
     */
    protected $slugify;

    /**
     * Constructor
     *
     * @param EntityManager $manager Entity manager
     * @param Slugify       $slugify Slugify
     */
    public function __construct(EntityManager $manager, Slugify $slugify)
    {
        $this->manager = $manager;
        $this->slugify = $slugify;
    }

    /**
     * Created slug by text
     *
     * @param string $text Text
     *
     * @return array
     */
    public function createUniqueSlug($text)
    {
        $translator     = new Translator('en_ER');
        $textTranslated = $translator->trans($text);
        $slug           = $this->slugify->slugify($textTranslated);

        $sight = $this->manager->getRepository('AppBundle:Sight')->findSightBySlug($slug);
        if (null === $sight) {
            return [
                'unique' => true,
                'value'  => $slug,
            ];
        }

        return [
            'unique' => false,
            'value'  => $slug,
        ];
    }
}
