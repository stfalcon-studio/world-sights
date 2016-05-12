<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTour;
use AppBundle\Exception\ServerInternalErrorException;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Sight Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sights_")
 * @Rest\Prefix("/v1/sights")
 */
class SightController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return all sights with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return all sights",
     *     section="Sight",
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
            $sightRepository = $this->getDoctrine()->getRepository('AppBundle:Sight');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $sights = $sightRepository->findSightsWithPagination($paginator);
            } else {
                $sights = $sightRepository->findAllSights();
            }

            $view = $this->createViewForHttpOkResponse([
                'sights' => $sights,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return sight by slug
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return sight by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *     section="Sight",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getAction(Sight $sight)
    {
        if (!$sight->isEnabled()) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);

            return $this->handleView($view);
        }

        $view = $this->createViewForHttpOkResponse([
            'sight' => $sight,
        ]);
        $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));

        return $this->handleView($view);
    }

    /**
     * Return tickets by sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return tickets by sight",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *     section="Sight",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight type not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}/tickets")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getTicketAction(Sight $sight)
    {
        try {
            $sightTickets = $this->getDoctrine()->getRepository('AppBundle:SightTicket')
                                 ->findSightTicketsBySight($sight);

            $view = $this->createViewForHttpOkResponse([
                'sight_tickets' => $sightTickets,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_ticket_for_sight']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return tours by sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return tours by sight",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *     section="Sight",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight type not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}/tours")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getToursAction(Sight $sight)
    {
        try {
            $sightTours = $this->getDoctrine()->getRepository('AppBundle:SightTour')->findSightToursBySight($sight);

            $view = $this->createViewForHttpOkResponse([
                'sight_tours' => $sightTours,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_tour_for_sight']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create sight
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight",
     *      description="Create a new sight",
     *      input="AppBundle\Form\Type\SightType",
     *      output={
     *          "class"="AppBundle\Entity\Sight",
     *          "groups"={"sight"}
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
        $form = $this->createForm(SightType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var Sight $sight */
                $sight = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sight);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight' => $sight]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Update sight
     *
     * @param Request $request Request
     * @param Sight   $sight   Sight
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight",
     *      description="Update sight",
     *      input="AppBundle\Form\Type\SightType",
     *      output={
     *          "class"="AppBundle\Entity\Sight",
     *          "groups"={"sight"}
     *      },
     *      requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
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
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function updateAction(Request $request, Sight $sight)
    {
        $form = $this->createForm(SightType::class, $sight);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var Sight $sight */
                $sight = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sight);
                $em->flush();

                $view = $this->createViewForHttpOkResponse(['sight' => $sight]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Delete sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *      section="Sight",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function deleteAction(Sight $sight)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sight);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        $view = $this->createViewForHttpNoContentResponse();

        return $view;
    }
}
