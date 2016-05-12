<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTour;
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
     * Get all sights
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get all sights",
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
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $sights = $sightRepository->findSightsWithPagination($pagination);
                $total  = $sightRepository->getTotalNumberOfEnabledSights();

                $view = $this->createViewForHttpOkResponse([
                    'sights'    => $sights,
                    '_metadata' => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Get sight by slug
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight by slug",
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
        try {
            if (!$sight->isEnabled()) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Sight not Found',
                ]);

                return $this->handleView($view);
            }

            $view = $this->createViewForHttpOkResponse([
                'sight' => $sight,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Get tickets by sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get tickets by sight",
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
     * Get tours by sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get tours by sight",
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
        try {
            $form = $this->createForm(SightType::class);

            $form->submit($request->request->all());
            if ($form->isValid()) {
                /** @var Sight $sight */
                $sight = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sight);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight' => $sight]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Update sight
     *
     * @param Request $request Request
     * @param Sight   $sight   Sight
     *
     * @return Response
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
        try {
            $form = $this->createForm(SightType::class, $sight);

            $form->submit($request->request->all());
            if ($form->isValid()) {
                /** @var Sight $sight */
                $sight = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sight);
                $em->flush();

                $view = $this->createViewForHttpOkResponse(['sight' => $sight]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Delete sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
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

        return $this->createViewForHttpNoContentResponse();
    }
}
