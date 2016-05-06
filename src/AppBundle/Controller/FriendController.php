<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Entity\Friend;
use AppBundle\Entity\User;
use AppBundle\Exception\ServerInternalErrorException;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\FriendType;
use AppBundle\Form\Type\PaginationType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Friend Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_friends_")
 * @Rest\Prefix("/v1/friends")
 */
class FriendController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return accepted friends with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return accepted friends",
     *     section="Friend",
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
            $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

            $user         = $this->getUser();
            $statusFriend = FriendStatusType::ACCEPTED;

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $friends = $userRepository->findFriendUsersByUserWithPagination($user, $statusFriend, $paginator);
            } else {
                $friends = $userRepository->findFriendUsersByUser($user, $statusFriend);
            }

            $view = $this->createViewForHttpOkResponse([
                'friends'            => $friends,
                'friend_status_type' => $statusFriend,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return received friends with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return received friends",
     *     section="Friend",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/received")
     */
    public function getReceivedFriendsAction(Request $request)
    {
        try {
            $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

            $user         = $this->getUser();
            $statusFriend = FriendStatusType::RECEIVED;

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $friends = $userRepository->findFriendUsersByUserWithPagination($user, $statusFriend, $paginator);
            } else {
                $friends = $userRepository->findFriendUsersByUser($user, $statusFriend);
            }

            $view = $this->createViewForHttpOkResponse([
                'friends'            => $friends,
                'friend_status_type' => $statusFriend,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return rejected friends with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return rejected friends",
     *     section="Friend",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/rejected")
     */
    public function getRejectedFriendsAction(Request $request)
    {
        try {
            $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

            $user         = $this->getUser();
            $statusFriend = FriendStatusType::REJECTED;

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $friends = $userRepository->findFriendUsersByUserWithPagination($user, $statusFriend, $paginator);
            } else {
                $friends = $userRepository->findFriendUsersByUser($user, $statusFriend);
            }

            $view = $this->createViewForHttpOkResponse([
                'friends'            => $friends,
                'friend_status_type' => $statusFriend,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return friends with status sent and pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return friends with status sent",
     *     section="Friend",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/sent")
     */
    public function getSentFriendsAction(Request $request)
    {
        try {
            $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

            $user         = $this->getUser();
            $statusFriend = FriendStatusType::SENT;

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $friends = $userRepository->findFriendUsersByUserWithPagination($user, $statusFriend, $paginator);
            } else {
                $friends = $userRepository->findFriendUsersByUser($user, $statusFriend);
            }

            $view = $this->createViewForHttpOkResponse([
                'friends'            => $friends,
                'friend_status_type' => $statusFriend,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return friend by id
     *
     * @param User $friend Friend
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return friend by id",
     *     requirements={
     *          {"name"="id", "dataType"="int", "requirement"="\d+", "description"="ID of friend"}
     *      },
     *     section="Friend",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when friend not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{id}", requirements = {"id" = "^(?!(status-types)|(received)|(rejected)|(sent)).*"})
     *
     * @ParamConverter("id", class="AppBundle:User")
     */
    public function getAction(User $friend)
    {
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        $user   = $this->getUser();
        $friend = $userRepository->findUserByUserFriend($user, $friend);

        if (null === $friend) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);
        } else {
            $status = $userRepository->findFriendStatusByUserAndFriend($user, $friend);

            $friend->setStatus($status['status']);
            $view = $this->createViewForHttpOkResponse([
                'friend' => $friend,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
        }

        return $this->handleView($view);
    }

    /**
     * Return friends status
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return friend status types",
     *     section="Friend",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/status-types")
     */
    public function getFriendStatusAction()
    {
        try {
            $friendStatusTypes = FriendStatusType::getChoices();

            $view = $this->createViewForHttpOkResponse([
                'friend_status_types' => $friendStatusTypes,
            ]);
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create friend
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Friend",
     *      description="Create a new friend",
     *      input="AppBundle\Form\Type\FriendType",
     *      output={
     *          "class"="AppBundle\Entity\Friend",
     *          "groups"={"friend"}
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
        $form = $this->createForm(FriendType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var Friend $friend */
                $friend = $form->getData();
                if (FriendStatusType::SENT === $friend->getStatus()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($friend);
                    $em->flush();

                    $view = $this->createViewForHttpCreatedResponse(['friend' => $friend]);
                    $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
                } else {
                    $form->get('status')->addError(new FormError('Status must be sent'));
                    $view = $this->createViewForValidationErrorResponse($form);
                }
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
     * @param Request $request Request
     * @param Friend  $friend  Friend
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Friend",
     *      description="Update a friend",
     *      input="AppBundle\Form\Type\FriendType",
     *      output={
     *          "class"="AppBundle\Entity\Friend",
     *          "groups"={"friend"}
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
     * @ParamConverter("id", class="AppBundle:User")
     */
    public function updateAction(Request $request, User $userFriend)
    {
        $user   = $this->getUser();
        $friend = $this->getDoctrine()->getRepository('AppBundle:Friend')->findFriendByUserFriend($user, $userFriend);
        if (null === $friend) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);
        } else {
            $form = $this->createForm(FriendType::class, $friend);

            $form->submit($request->request->all(), false);
            if ($form->isValid()) {
                /** @var Friend $friend */
                $friend = $form->getData();

                /** @var View $view */
                $view = $this->get('app.friend_status')->updateFriendStatus($friend, $form);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['friend']));
            } else {
                $view = $this->createViewForValidationErrorResponse($form);
            }
        }

        return $this->handleView($view);
    }

    /**
     * Delete friend
     *
     * @param User $friend Friend
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="id", "dataType"="int", "requirement"="\d+", "description"="ID of friend"}
     *      },
     *      section="Friend",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{id}")
     *
     * @ParamConverter("id", class="AppBundle:User")
     */
    public function deleteAction(User $friend)
    {
        $user   = $this->getUser();
        $friend = $this->getDoctrine()->getRepository('AppBundle:Friend')->findFriendByUserFriend($user, $friend);

        if (null === $friend) {
            $view = $this->createViewForHttpNotFoundResponse([
                'message' => 'Not Found',
            ]);
        } else {
            if (FriendStatusType::SENT === $friend->getStatus()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($friend);
                $em->flush();

                $view = $this->createViewForHttpNoContentResponse();
            } else {
                $view = $this->createViewForInvalidErrorResponse([
                    'message' => 'For delete friend status must be sent',
                ]);
            }
        }

        return $this->handleView($view);
    }
}
