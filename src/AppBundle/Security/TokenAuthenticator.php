<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;

class TokenAuthenticator extends AbstractGuardAuthenticator
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
     * Called on every request. Return whatever credentials you want, or exception
     *
     * @param Request $request Request
     *
     * @throws UnauthorizedHttpException
     *
     * @return array|null
     */
    public function getCredentials(Request $request)
    {
        $route = $request->get('_route');
        if ('api_users_registration' === $route
            || 'api_users_login' === $route
            || 'nelmio_api_doc_index' === $route
            || 'api_users_update_token' === $route
        ) {
            return null;
        }

        $token = $request->headers->get('X-AUTH-TOKEN');
        if (null === $token) {
            throw new UnauthorizedHttpException(401, 'Auth token is required');
        }

        return [
            'token' => $token,
        ];
    }

    /**
     * Get user
     *
     * @param mixed                 $credentials  Credentials
     * @param UserProviderInterface $userProvider User provider
     *
     * @return User
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $accessToken = $credentials['token'];

        return $this->em->getRepository('AppBundle:User')->findOneBy([
            'accessToken' => $accessToken,
        ]);
    }

    /**
     * Check credentials - e.g. make sure the password is valid
     * no credential check is needed in this case
     *
     * @param mixed $credentials Credentials
     * @param User  $user        User
     *
     * @throws UnauthorizedHttpException
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (new \DateTime() > $user->getExpiredAt()) {
            throw new UnauthorizedHttpException(401, 'Access token is expired');
        }

        return true;
    }

    /**
     * On authentication success
     *
     * @param Request        $request     Request
     * @param TokenInterface $token       Token
     * @param string         $providerKey Provider key
     *
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * On authentication failure
     *
     * @param Request                 $request   Request
     * @param AuthenticationException $exception Exception
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ], 401);
    }

    /**
     * Called when authentication is needed, but it's not sent
     *
     * @param Request                      $request Request
     * @param AuthenticationException|null $authException
     *
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'message' => 'Authentication Required',
        ], 401);
    }

    /**
     * Supports remember me
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
