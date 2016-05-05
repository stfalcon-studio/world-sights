<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\FriendStatusType;
use AppBundle\Entity\User;
use AppBundle\Exception\ServerInternalErrorException;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Rest\Get("/{id}", requirements = {"id" = "^(?!(status-types)|(rejected)|(sent)).*"})
     *
     * @ParamConverter("id", class="AppBundle:User")
     */
    public function getAction(User $friend)
    {
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        $user   = $this->getUser();
        $friend = $userRepository->findFriendUserByUser($user, $friend);

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
}
