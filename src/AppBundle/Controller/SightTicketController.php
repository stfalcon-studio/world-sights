<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\SightTicketType as SightTicketTypeDBAL;
use AppBundle\Entity\SightTicket;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightTicketType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Sight Ticket Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sight_tickets_")
 * @Rest\Prefix("/v1/sight-tickets")
 */
class SightTicketController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return all sight tickets with pagination
     *
     * @param Request $request Request
     *
     * @return SightTicket[]
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return all sight ticket",
     *     section="Sight Ticket",
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
            $sightTicketRepository = $this->getDoctrine()->getRepository('AppBundle:SightTicket');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $sightTickets = $sightTicketRepository->findSightTicketsWithPagination($paginator);
            } else {
                $sightTickets = $sightTicketRepository->findAllSightTickets();
            }

            $view = $this->createViewForHttpOkResponse([
                'sight_tickets' => $sightTickets,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_ticket']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return sight ticket by slug
     *
     * @param SightTicket $sightTicket SightTicket
     *
     * @return SightTicket
     *
     * @ApiDoc(
     *     description="Return sight ticket by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight ticket"}
     *      },
     *     section="Sight Ticket",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}", requirements = {"slug" = "^(?!types).*"})
     *
     * @ParamConverter("sightTicket", class="AppBundle:SightTicket")
     */
    public function getAction(SightTicket $sightTicket)
    {
        if (!$sightTicket->isEnabled()) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);

            return $this->handleView($view);
        }

        $view = $this->createViewForHttpOkResponse([
            'sight_ticket' => $sightTicket,
        ]);
        $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_ticket']));

        return $this->handleView($view);
    }

    /**
     * Return sight ticket types
     *
     * @return SightTicketTypeDBAL[]
     *
     * @ApiDoc(
     *     description="Return sight ticket types",
     *     section="Sight Ticket",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/types")
     */
    public function getTypesAction()
    {
        try {
            $types = SightTicketTypeDBAL::getChoices();

            $view = $this->createViewForHttpOkResponse([
                'sight_ticket_types' => $types,
            ]);
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create sight ticket
     *
     * @param Request $request Request
     *
     * @return SightTicket
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight Ticket",
     *      description="Create a new sight ticket",
     *      input="AppBundle\Form\Type\SightTicketType",
     *      output={
     *          "class"="AppBundle\Entity\SightTicket",
     *          "groups"={"sight_ticket"}
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
        $form = $this->createForm(SightTicketType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var SightTicket $sightTicket */
                $sightTicket = $form->getData();

                $slug = $this->get('app.slug')->createSlugSightTicket($sightTicket);

                $sightTicket->setSlug($slug);

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightTicket);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight_ticket' => $sightTicket]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_ticket']));
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
     * Update sight ticket
     *
     * @param Request     $request     Request
     * @param SightTicket $sightTicket Sight Ticket
     *
     * @return SightTicket
     *
     * @ApiDoc(
     *      section="Sight Ticket",
     *      description="Update sight ticket",
     *      input="AppBundle\Form\Type\SightTicketType",
     *      output={
     *          "class"="AppBundle\Entity\SightTicket",
     *          "groups"={"sight_ticket"}
     *      },
     *      requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight ticket"}
     *      },
     *      statusCodes={
     *          201="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Put("/{slug}")
     *
     * @ParamConverter("sightTicket", class="AppBundle:SightTicket")
     */
    public function updateAction(Request $request, SightTicket $sightTicket)
    {
        $form = $this->createForm(SightTicketType::class, $sightTicket);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var SightTicket $sightTicket */
                $sightTicket = $form->getData();

                $slug = $this->get('app.slug')->createSlugSightTicket($sightTicket);

                $sightTicket->setSlug($slug);

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightTicket);
                $em->flush();

                $view = $this->createViewForHttpOkResponse(['sight_ticket' => $sightTicket]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_ticket']));
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
     * Delete Sight Ticket
     *
     * @param SightTicket $sightTicket Sight ticket
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight ticket"}
     *      },
     *      section="Sight Ticket",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{slug}")
     *
     * @ParamConverter("sightTicket", class="AppBundle:SightTicket")
     */
    public function deleteAction(SightTicket $sightTicket)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sightTicket);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        $view = $this->createViewForHttpNoContentResponse();

        return $view;
    }
}
