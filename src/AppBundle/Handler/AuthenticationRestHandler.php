<?php

namespace AppBundle\Handler;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Authentication Rest Handler
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class AuthenticationRestHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var EntityManager $em Entity manager
     */
    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $em Entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * On authentication failure
     *
     * @param Request                 $request   Request
     * @param AuthenticationException $exception Authentication exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => 'username or password is not correct',
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * On authentication success
     *
     * @param Request        $request Request
     * @param TokenInterface $token   Token
     *
     * @return JsonResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        $user->setAccessToken(bin2hex(random_bytes(15)))
             ->setRefreshToken(bin2hex(random_bytes(15)))
             ->setExpiredAt((new \DateTime())->modify('12 hour'));

        $this->em->persist($user);
        $this->em->flush();

        $response = new JsonResponse([
            'accessToken'  => $user->getAccessToken(),
            'refreshToken' => $user->getRefreshToken(),
        ], Response::HTTP_OK);

        return $response;
    }
}
