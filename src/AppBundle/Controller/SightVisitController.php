<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightVisit;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightVisitType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Sight Visit Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sight_visits_")
 * @Rest\Prefix("/v1/sight-visits")
 */
class SightVisitController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return all visited sights by user with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return all visited sights by user",
     *     section="Sight Visit",
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
            $user            = $this->getUser();

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $sights = $sightRepository->findSightBySightVisitUserWithPagination($user, $paginator);
            } else {
                $sights = $sightRepository->findSightBySightVisitUser($user);
            }

            $view = $this->createViewForHttpOkResponse([
                'sight_visits' => $sights,
                'user'         => $user,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_visits']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return all visited sights by all friends with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return all visited sights by all friends",
     *     section="Sight Visit",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/friends")
     */
    public function getAllFriendsAction(Request $request)
    {
        try {
            $sightRepository = $this->getDoctrine()->getRepository('AppBundle:Sight');
            $user            = $this->getUser();

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $resultSights = $sightRepository->findSightBySightVisitByFriendsWithPagination($user, $paginator);
            } else {
                $resultSights = $sightRepository->findSightBySightVisitByFriends($user);
            }

            $sights = [];
            foreach ($resultSights as $resultSight) {
                /** @var Sight $sight */
                $sight = $resultSight[0];
                $sight->setUser($resultSight['user']);

                $sights[] = $sight;
            }

            $view = $this->createViewForHttpOkResponse([
                'sight_visits_friends' => $sights,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_visits_friends']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return sight visit by friend
     *
     * @param User    $friend  User
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return sight visit by id",
     *     requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of friend"}
     *      },
     *     section="Sight Visit",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/friends/{id}")
     *
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function getFriendAction(User $friend, Request $request)
    {
        try {
            $user = $this->getUser();

            $userFriend = $this->getDoctrine()->getRepository('AppBundle:Friend')
                               ->findFriendByUserFriend($user, $friend);
            if (null === $userFriend) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Not Found',
                ]);
            } else {
                $form = $this->createForm(PaginationType::class);

                $form->submit($request->query->all());
                if ($form->isValid()) {
                    /** @var Pagination $paginator */
                    $paginator = $form->getData();

                    $sights = $this->getDoctrine()->getRepository('AppBundle:Sight')
                                   ->findSightBySightVisitUserWithPagination($user, $paginator);
                } else {
                    $sights = $this->getDoctrine()->getRepository('AppBundle:Sight')
                                   ->findSightBySightVisitUser($friend);
                }

                $view = $this->createViewForHttpOkResponse([
                    'sight_visits' => $sights,
                    'friend'       => $friend,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_visits']));
            }
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return sight visit by user
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return sight visit by id",
     *     requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of sight visit"}
     *      },
     *     section="Sight Visit",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{id}", requirements = {"id" = "^(?!friends).*"})
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getAction(Sight $sight)
    {
        try {
            $user       = $this->getUser();
            $sightVisit = $this->getDoctrine()->getRepository('AppBundle:SightVisit')
                               ->findSightVisitBySightAndUser($sight, $user);
            if (null === $sightVisit) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Not Found',
                ]);
            } else {
                $view = $this->createViewForHttpOkResponse([
                    'sight_visit' => $sight,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_visits']));
            }
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create sight visit
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight Visit",
     *      description="Create a new sight visit",
     *      input="AppBundle\Form\Type\SightVisitType",
     *      output={
     *          "class"="AppBundle\Entity\SightVisit",
     *          "groups"={"sight_visit"}
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
        $form = $this->createForm(SightVisitType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var SightVisit $sightVisit */
                $sightVisit = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightVisit);
                $em->flush();

                $view = $this->createViewForHttpOkResponse([
                    'sight_visits' => $sightVisit->getSight(),
                    'user'         => $this->getUser(),
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_visits']));
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
     * Update sight visit
     *
     * @param Request $request Request
     * @param Sight   $sight   Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Visit",
     *      description="Update sight visit",
     *      input="AppBundle\Form\Type\SightVisitType",
     *      output={
     *          "class"="AppBundle\Entity\SightVisit",
     *          "groups"={"sight_visit"}
     *      },
     *      requirements={
     *          {"name"="id", "dataType"="int", "requirement"="\d+", "description"="ID of sight"}
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Put("/{id}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function updateAction(Request $request, Sight $sight)
    {
        $user = $this->getUser();

        $sightVisit = $this->getDoctrine()->getRepository('AppBundle:SightVisit')
                           ->findSightVisitBySightAndUser($sight, $user);
        if (null === $sightVisit) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);

            return $view;
        }

        $form = $this->createForm(SightVisitType::class, $sightVisit);

        $form->submit($request->request->all(), null);
        if ($form->isValid()) {
            try {
                /** @var SightVisit $sightVisit */
                $sightVisit = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightVisit);
                $em->flush();

                $view = $this->createViewForHttpOkResponse([
                    'sight_visits' => $sightVisit->getSight(),
                    'user'         => $this->getUser(),
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_visits']));
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
     * Delete sight visit
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight Visit",
     *      statusCodes={
     *          204="Returned when successful",
     *          400="Validation error",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{id}")
     *
     * @ParamConverter("id", class="AppBundle:Sight")
     */
    public function deleteAction(Sight $sight)
    {
        try {
            $user       = $this->getUser();
            $sightVisit = $this->getDoctrine()->getRepository('AppBundle:SightVisit')
                               ->findSightVisitBySightAndUser($sight, $user);
            if (null === $sightVisit) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Not Found',
                ]);
            } else {
                $em = $this->getDoctrine()->getManager();
                $em->persist($sightVisit);
                $em->flush();

                $view = $this->createViewForHttpNoContentResponse();
            }
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }
}
