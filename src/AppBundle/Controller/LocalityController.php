<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Locality;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Model\Pagination;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * Get all localities
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get all localities",
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
                $total      = $localityRepository->getTotalNumberOfEnabledLocalities();

                $view = $this->createViewForHttpOkResponse([
                    'localities' => $localities,
                    '_metadata'  => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
            } else {
                $view = $this->createViewForValidationErrorResponse($form);
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
     *     description="Get locality by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of locality"}
     *      },
     *     section="Locality",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when locality not found",
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
        try {
            if (!$locality->isEnabled()) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Locality not Found',
                ]);

                return $this->handleView($view);
            }

            $view = $this->createViewForHttpOkResponse([
                'locality' => $locality,
            ]);
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }
}
