<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightRecommend;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Sight Recommend Controller
 *
 * @author Yevgeniy Zholkevskiy <zhenya.zholkevskiy@gmail.com>
 *
 * @Rest\NamePrefix("api_sight_recommend_")
 * @Rest\Prefix("/v1/sight-recommend")
 */
class SightRecommendController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Get all sight recommends
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get all sight recommends",
     *     section="Sight Recommend",
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
            $sightRecommendRepository = $this->getDoctrine()->getRepository('AppBundle:SightRecommend');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $user = $this->getUser();

                $sightRecommends = $sightRecommendRepository->findSightRecommendsByUserWithPagination($user, $pagination);
                $total           = $sightRecommendRepository->getTotalNumberOfEnabledSightRecommendsByUser($user);

                $view = $this->createViewForHttpOkResponse([
                    'sight_recommends' => $sightRecommends,
                    '_metadata'        => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_recommend']));
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
     * Get sight recommend
     *
     * @param SightRecommend $sightRecommend Sight Recommend
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight recommend",
     *     section="Sight Recommend",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight recommend not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{id}")
     * @ParamConverter("id", class="AppBundle:SightRecommend")
     */
    public function getAction(SightRecommend $sightRecommend)
    {
        try {
            if (!$sightRecommend->isEnabled()) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Sight recommend not Found',
                ]);
            } else {
                $view = $this->createViewForHttpOkResponse([
                    'sight_recommend' => $sightRecommend,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_recommend']));
            }
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Get sight recommends by user
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight recommends by user",
     *     requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of user"}
     *      },
     *     section="Sight Recommend",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when user not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/users/{id}")
     *
     * @ParamConverter("id", class="AppBundle:User")
     */
    public function getUserAction(Request $request, User $user)
    {
        try {
            $sightRecommendRepository = $this->getDoctrine()->getRepository('AppBundle:SightRecommend');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $sightRecommends = $sightRecommendRepository->findSightRecommendsByUserWithPagination($user, $pagination);
                $total           = $sightRecommendRepository->getTotalNumberOfEnabledSightRecommendsByUser($user);

                $view = $this->createViewForHttpOkResponse([
                    'sight_recommends' => $sightRecommends,
                    '_metadata'        => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_recommend']));
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
     * Get sight recommends by sight
     *
     * @param Request $request Request
     * @param Sight   $sight   Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight recommends by sight",
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
     * @Rest\Get("/sights/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getSightAction(Request $request, Sight $sight)
    {
        try {
            $sightRecommendRepository = $this->getDoctrine()->getRepository('AppBundle:SightRecommend');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $user = $this->getUser();

                $sightRecommends = $sightRecommendRepository->findSightRecommendsByUserWithPagination($user, $pagination);
                $total           = $sightRecommendRepository->getTotalNumberOfEnabledSightRecommendsBySight($sight);

                $view = $this->createViewForHttpOkResponse([
                    'sight_recommends' => $sightRecommends,
                    '_metadata'        => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_recommend']));
            } else {
                $view = $this->createViewForValidationErrorResponse($form);
            }
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }
}
