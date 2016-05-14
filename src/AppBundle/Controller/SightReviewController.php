<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightReview;
use AppBundle\Entity\User;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightReviewType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Sight Review Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sights_")
 * @Rest\Prefix("/v1/sight-reviews")
 */
class SightReviewController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Get all sight reviews
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get all sight reviews",
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
            $sightsReviewRepository = $this->getDoctrine()->getRepository('AppBundle:SightReview');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $user = $this->getUser();

                $sightsReviews = $sightsReviewRepository->findSightReviewsWithPagination($pagination);
                $total         = $sightsReviewRepository->getTotalNumberOfEnabledSightReviewsByUser($user);

                $view = $this->createViewForHttpOkResponse([
                    'sight_reviews' => $sightsReviews,
                    '_metadata'     => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_review']));
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
     * Get sight review
     *
     * @param SightReview $sightReview Sight Review
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight review",
     *     section="Sight Review",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight review not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{id}")
     * @ParamConverter("id", class="AppBundle:SightReview")
     */
    public function getAction(SightReview $sightReview)
    {
        try {
            if (!$sightReview->isEnabled()) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Sight review not Found',
                ]);

                return $this->handleView($view);
            }

            $view = $this->createViewForHttpOkResponse([
                'sight_review' => $sightReview,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_review']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Get sight reviews by sight
     *
     * @param Request $request Request
     * @param Sight   $sight   Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight reviews by sight",
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
            $sightReviewRepository = $this->getDoctrine()->getRepository('AppBundle:SightReview');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $sightReviews = $sightReviewRepository->findSightReviewsBySightWithPagination($sight, $pagination);
                $averageMark  = $sightReviewRepository->getAverageMarkBySight($sight);

                $view = $this->createViewForHttpOkResponse([
                    'sight_reviews' => $sightReviews,
                    'average_mark'  => $averageMark,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_review']));
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
     * Get sight reviews by user
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get sight reviews by user",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of user"}
     *      },
     *     section="Sight",
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
            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $sightReviews = $this->getDoctrine()->getRepository('AppBundle:SightReview')
                                     ->findSightReviewsByUserWithPagination($user, $pagination);

                $view = $this->createViewForHttpOkResponse([
                    'sight_reviews' => $sightReviews,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_review']));
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
     * Create sight review
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Review",
     *      description="Create a new sight review",
     *      input="AppBundle\Form\Type\SightReviewType",
     *      output={
     *          "class"="AppBundle\Entity\SightReview",
     *          "groups"={"sight_review"}
     *      },
     *      statusCodes={
     *          201="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Post("")
     * @ParamConverter(class="AppBundle:SightReview", converter="sight_review_converter")
     */
    public function createAction(Request $request)
    {
        try {
            $form = $this->createForm(SightReviewType::class);

            $form->submit($request->request->all());
            if ($form->isValid()) {
                /** @var SightReview $sightReview */
                $sightReview = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightReview);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight_review' => $sightReview]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_review']));
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
     * Update sight review
     *
     * @param Request     $request     Request
     * @param SightReview $sightReview Sight review
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Review",
     *      description="Update sight review",
     *      input="AppBundle\Form\Type\SightReviewType",
     *      output={
     *          "class"="AppBundle\Entity\SightReview",
     *          "groups"={"sight"}
     *      },
     *      requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of sight"}
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
     * @ParamConverter(class="AppBundle:SightReview", converter="sight_review_converter")
     * @ParamConverter("sight", class="AppBundle:SightReview")
     */
    public function updateAction(Request $request, SightReview $sightReview)
    {
        try {
            $form = $this->createForm(SightReviewType::class, $sightReview);

            $form->submit($request->request->all(), false);
            if ($form->isValid()) {
                $user = $this->getUser();
                if ($user === $sightReview->getUser()) {
                    /** @var SightReview $sightReview */
                    $sightReview = $form->getData();

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sightReview);
                    $em->flush();

                    $view = $this->createViewForHttpOkResponse(['sight_review' => $sightReview]);
                    $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_review']));
                } else {
                    $form->get('user')->addError(new FormError('User must be author review'));

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
     * Delete sight review
     *
     * @param SightReview $sightReview Sight review
     *
     * @return Response
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of sight review"}
     *      },
     *      section="Sight Review",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{id}")
     *
     * @ParamConverter("id", class="AppBundle:SightReview")
     */
    public function deleteAction(SightReview $sightReview)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sightReview);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->createViewForHttpNoContentResponse();
    }
}
