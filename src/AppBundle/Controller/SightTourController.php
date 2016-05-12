<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SightTour;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightTourType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sight Tour Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sight_tours_")
 * @Rest\Prefix("/v1/sight-tours")
 */
class SightTourController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Get all sight tours
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get all sight tours",
     *     section="Sight Tour",
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
            $sightTourRepository = $this->getDoctrine()->getRepository('AppBundle:SightTour');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $sightTours = $sightTourRepository->findSightToursWithPagination($paginator);
                $total      = $sightTourRepository->getTotalNumberOfEnabledSightTours();

                $view = $this->createViewForHttpOkResponse([
                    'sight_tours' => $sightTours,
                    '_metadata'   => [
                        'total'  => $total,
                        'limit'  => $paginator->getLimit(),
                        'offset' => $paginator->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_tour']));
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
     * Get sight tour by slug
     *
     * @param SightTour $sightTour Sight tour
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight tour by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight tour"}
     *      },
     *     section="Sight Tour",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight tour not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}")
     *
     * @ParamConverter("sightTour", class="AppBundle:SightTour")
     */
    public function getAction(SightTour $sightTour)
    {
        try {
            if (!$sightTour->isEnabled()) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Sight tour not Found',
                ]);

                return $this->handleView($view);
            }

            $view = $this->createViewForHttpOkResponse([
                'sight_tour' => $sightTour,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_tour']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create sight tour
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Tour",
     *      description="Create a new sight tour",
     *      input="AppBundle\Form\Type\SightTourType",
     *      output={
     *          "class"="AppBundle\Entity\SightTour",
     *          "groups"={"sight_tour"}
     *      },
     *      statusCodes={
     *          201="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Post("")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(SightTourType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var SightTour $sightTour */
                $sightTour = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightTour);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight_tour' => $sightTour]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_tour']));
            } catch (\Exception $e) {
                $this->sendExceptionToRollbar($e);
                throw $this->createInternalServerErrorException();
            }
        } else {
            $view = $this->createViewForValidationErrorResponse($form);
        }

        return $this->handleView($view);
    }

    /**
     * Update sight tour
     *
     * @param Request   $request   Request
     * @param SightTour $sightTour Sight Tour
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Tour",
     *      description="Update sight tour",
     *      input="AppBundle\Form\Type\SightTourType",
     *      output={
     *          "class"="AppBundle\Entity\SightTour",
     *          "groups"={"sight_tour"}
     *      },
     *      requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight tour"}
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Put("/{slug}")
     *
     * @ParamConverter("sightTour", class="AppBundle:SightTour")
     */
    public function updateAction(Request $request, SightTour $sightTour)
    {
        try {
            $form = $this->createForm(SightTourType::class, $sightTour);

            $form->submit($request->request->all());
            if ($form->isValid()) {
                /** @var SightTour $sightTour */
                $sightTour = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightTour);
                $em->flush();

                $view = $this->createViewForHttpOkResponse(['sight_tour' => $sightTour]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_tour']));
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
     * Delete sight tour
     *
     * @param SightTour $sightTour Sight tour
     *
     * @return Response
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight tour"}
     *      },
     *      section="Sight Tour",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{slug}")
     *
     * @ParamConverter("sightTour", class="AppBundle:SightTour")
     */
    public function deleteAction(SightTour $sightTour)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sightTour);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->createViewForHttpNoContentResponse();
    }
}
