<?php

namespace AppBundle\Service;

use AppBundle\Entity\SightPhoto;
use Symfony\Component\Routing\Router;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * SightPhotoService
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class SightPhotoService
{
    /**
     * @var UploaderHelper $helper Helper
     */
    private $helper;

    /**
     * @var Request $request Request
     */
    private $request;

    /**
     * Constructor
     *
     * @param UploaderHelper $helper Helper
     * @param Router         $router Router
     */
    public function __construct(UploaderHelper $helper, Router $router)
    {
        $this->helper = $helper;
        $this->router = $router;
    }

    /**
     * Get path image
     *
     * @param SightPhoto $sightPhoto Sight photo
     *
     * @return string
     */
    public function getPathImage(SightPhoto $sightPhoto)
    {
        $path = $this->helper->asset($sightPhoto, 'photoFile');
        $url  = sprintf('%s://%s%s', 'http', $this->router->getContext()->getHost(), $path);

        return $url;
    }
}
