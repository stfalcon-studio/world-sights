<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\RefreshTokenType;
use AppBundle\Form\Type\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * User Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_users_")
 * @Rest\Prefix("/v1/users")
 */
class UserController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Registration new user
     *
     * @param Request $request Request
     *
     * @throws ServerInternalErrorException
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="User",
     *      description="Registration user",
     *      input="AppBundle\Form\Type\UserType",
     *      output={
     *          "class"="AppBundle\Entity\User",
     *          "groups"={"user"}
     *      },
     *      statusCodes={
     *          201="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Post("/registration")
     */
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(UserType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                /** @var User $user */
                $user = $form->getData();

                $user->setPlainPassword($user->getPassword())
                     ->setEnabled(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['user' => $user]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['user']));
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
     * Update access token by refresh token
     *
     * @param Request $request Request
     *
     * @throws ServerInternalErrorException
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="User",
     *      description="Update user token",
     *      input="AppBundle\Form\Type\RefreshTokenType",
     *      output={
     *          "class"="AppBundle\Entity\User",
     *          "groups"={"user"}
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Post("/update-token")
     */
    public function updateTokenAction(Request $request)
    {
        $form = $this->createForm(RefreshTokenType::class);

        $form->submit($request->request->all());
        if ($form->isValid()) {
            try {
                $refreshToken = $form->getData()['refresh_token'];

                $em = $this->getDoctrine()->getEntityManager();

                /** @var User $user */
                $user = $em->getRepository('AppBundle:User')->findUserByRefreshToken($refreshToken);
                if (null === $user) {
                    $view = $this->createViewForInvalidErrorResponse([
                        'message' => 'refresh token is invalid',
                    ]);

                    return $this->handleView($view);
                }

                $user->setAccessToken(bin2hex(random_bytes(15)))
                     ->setExpiredAt((new \DateTime())->modify('12 hour'));

                $em->persist($user);
                $em->flush();

                $view = $this->createViewForHttpOkResponse([
                    'user' => $user,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['user']));
            } catch (\Exception $e) {
                $this->sendExceptionToRollbar($e);
                throw $this->createInternalServerErrorException();
            }
        } else {
            $view = $this->createViewForValidationErrorResponse($form);
        }

        return $this->handleView($view);
    }
}