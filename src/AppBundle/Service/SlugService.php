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
     * @var Slugify $slugify Slugify
     */
    protected $slugify;

    /**
     * Constructor
     *
     * @param Slugify $slugify Slugify
     */
    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * Created slug by text
     *
     * @param string $text Text
     *
     * @return string
     */
    public function createSlug($text)
    {
        $translator     = new Translator('en_ER');
        $textTranslated = $translator->trans($text);
        $slug           = $this->slugify->slugify($textTranslated);

        return $slug;
    }
}
