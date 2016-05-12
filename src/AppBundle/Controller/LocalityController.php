<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Locality;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Model\Pagination;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

/**
 * Locality Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_locality_")
 * @Rest\Prefix("/v1/localities")
 */
class LocalityController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return all localities with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return all localities",
     *     section="Locality",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("")
     */
    public function getAllAction(Request $request)
    {
        try {
            $localityRepository = $this->getDoctrine()->getRepository('AppBundle:Locality');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $localities = $localityRepository->findLocalitiesWithPagination($pagination);

                $view = $this->createViewForHttpOkResponse([
                    'localities' => $localities,
                    '_metadata'  => [
                        'total'  => count($localities),
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
            } else {
                $localities = $localityRepository->findAllLocalities();

                $view = $this->createViewForHttpOkResponse([
                    'localities' => $localities,
                ]);
            }
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Get locality by slug
     *
     * @param Locality $locality Locality
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return locality by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of locality"}
     *      },
     *     section="Locality",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}")
     *
     * @ParamConverter("locality", class="AppBundle:Locality")
     */
    public function getAction(Locality $locality)
    {
        if (!$locality->isEnabled()) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);

            return $this->handleView($view);
        }

        $view = $this->createViewForHttpOkResponse([
            'locality' => $locality,
        ]);

        return $this->handleView($view);
    }
}
