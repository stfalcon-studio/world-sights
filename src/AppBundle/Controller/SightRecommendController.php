<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightRecommend;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightRecommendType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormError;
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

    /**
     * Update sight recommend
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Recommend",
     *      description="Create a new sight recommend",
     *      input="AppBundle\Form\Type\SightRecommendType",
     *      output={
     *          "class"="AppBundle\Entity\SightRecommend",
     *          "groups"={"sight_recommend"}
     *      },
     *      statusCodes={
     *          201="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Post("")
     * @ParamConverter(class="AppBundle:SightRecommend", converter="sight_recommend_converter")
     */
    public function createAction(Request $request)
    {
        try {
            $form = $this->createForm(SightRecommendType::class);

            $form->submit($request->request->all());
            if ($form->isValid()) {
                /** @var SightRecommend $sightRecommend */
                $sightRecommend = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightRecommend);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight_recommend' => $sightRecommend]);
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
     * Update sight recommend
     *
     * @param Request        $request        Request
     * @param SightRecommend $sightRecommend Sight recommend
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Recommend",
     *      description="Update sight recommend",
     *      input="AppBundle\Form\Type\SightRecommendType",
     *      output={
     *          "class"="AppBundle\Entity\SightRecommend",
     *          "groups"={"sight_recommend"}
     *      },
     *      requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of sight recommend"}
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
     * @ParamConverter(class="AppBundle:SightRecommend", converter="sight_recommend_converter")
     * @ParamConverter("sight", class="AppBundle:SightRecommend")
     */
    public function updateAction(Request $request, SightRecommend $sightRecommend)
    {
        try {
            $form = $this->createForm(SightRecommendType::class, $sightRecommend);

            $form->submit($request->request->all(), false);
            if ($form->isValid()) {
                $user = $this->getUser();
                if ($user === $sightRecommend->getUser()) {
                    /** @var SightRecommend $sightRecommend */
                    $sightRecommend = $form->getData();

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sightRecommend);
                    $em->flush();

                    $view = $this->createViewForHttpOkResponse(['sight_recommend' => $sightRecommend]);
                    $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_recommend']));
                } else {
                    $form->get('user')->addError(new FormError('User must be author sight recommend'));

                    $view = $this->createViewForValidationErrorResponse($form);
                }
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
     * Delete sight recommend
     *
     * @param SightRecommend $sightRecommend Sight recommend
     *
     * @return Response
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of sight recommend"}
     *      },
     *      section="Sight Recommend",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{id}")
     *
     * @ParamConverter("id", class="AppBundle:SightRecommend")
     */
    public function deleteAction(SightRecommend $sightRecommend)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sightRecommend);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->createViewForHttpNoContentResponse();
    }
}
