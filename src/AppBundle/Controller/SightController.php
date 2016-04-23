<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Entity\SightTour;
use AppBundle\Exception\ServerInternalErrorException;
use AppBundle\Form\Type\SightType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Sight Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sights_")
 * @Rest\Prefix("/v1/sights")
 */
class SightController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return all sights
     *
     * @return Sight[]
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return all sights",
     *     section="Sight",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("")
     */
    public function getAllAction()
    {
        try {
            $sights = $this->getDoctrine()->getRepository('AppBundle:Sight')->findAllSights();

            $view = $this->createViewForHttpOkResponse([
                'status' => 'OK',
                'sights' => $sights,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return sight by slug
     *
     * @param Sight $sight Sight
     *
     * @return Sight
     *
     * @ApiDoc(
     *     description="Return sight by slug",
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
     * @Rest\Get("/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getAction(Sight $sight)
    {
        $view = $this->createViewForHttpOkResponse([
            'status' => 'OK',
            'sight'  => $sight,
        ]);
        $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));

        return $this->handleView($view);
    }

    /**
     * Return tours by sight
     *
     * @param Sight $sight Sight
     *
     * @return SightType
     *
     * @ApiDoc(
     *     description="Return tours by sight",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *     section="Sight",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight type not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}/tours")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getToursAction(Sight $sight)
    {
        try {
            $sightTour = $this->getDoctrine()->getRepository('AppBundle:SightTour')->findSightTourBySight($sight);
            $view      = $this->createViewForHttpOkResponse([
                'status'     => 'OK',
                'sight_tour' => $sightTour,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_tour']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create sight
     *
     * @param Request $request Request
     *
     * @return Sight
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight",
     *      description="Create a new sight",
     *      input="AppBundle\Form\Type\SightType",
     *      output={
     *          "class"="AppBundle\Entity\Sight",
     *          "groups"={"sight"}
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
        $sight = new Sight();

        $form = $this->createForm(SightType::class, $sight);

        $data = $request->request->all();

        $form->submit($data);
        if ($form->isValid()) {
            try {
                /** @var Sight $sight */
                $sight = $form->getData();

                $slug = $this->get('app.slug')->createUniqueSlug($sight->getName());
                if (false === $slug['unique']) {
                    $form->get('name')->addError(new FormError('Name should be unique'));
                    $view = $this->createViewForValidationErrorResponse($form);

                    return $this->handleView($view);
                }

                $sight->setSlug($slug['value']);

                $em = $this->getDoctrine()->getManager();
                $em->persist($sight);
                $em->flush();

                $view = $this->createViewForHttpCreatedResponse(['sight' => $sight]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Update sight
     *
     * @param Request $request Request
     * @param Sight   $sight   Sight
     *
     * @return Sight
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *      section="Sight",
     *      description="Update sight",
     *      input="AppBundle\Form\Type\SightType",
     *      output={
     *          "class"="AppBundle\Entity\Sight",
     *          "groups"={"sight"}
     *      },
     *      requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *      statusCodes={
     *          201="Returned when successful",
     *          400="Returned when the form has errors or invalid data",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\View(serializerEnableMaxDepthChecks=true, serializerGroups="sight")
     * @Rest\Put("/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function updateAction(Request $request, Sight $sight)
    {
        $form = $this->createForm(SightType::class, $sight);

        $data = $request->request->all();

        $form->submit($data);
        if ($form->isValid()) {
            try {
                /** @var Sight $sight */
                $sight = $form->getData();

                $slug = $this->get('app.slug')->createUniqueSlug($sight->getName());
                if (false === $slug['unique'] && $sight->getSlug() != $slug['value']) {
                    $form->get('name')->addError(new FormError('Name should be unique'));
                    $view = $this->createViewForValidationErrorResponse($form);

                    return $this->handleView($view);
                }

                $sight->setSlug($slug['value']);

                $em = $this->getDoctrine()->getManager();
                $em->persist($sight);
                $em->flush();

                $view = $this->createViewForHttpOkResponse(['sight' => $sight]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight']));
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
     * Delete Sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of sight"}
     *      },
     *      section="Sight",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function deleteAction(Sight $sight)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sight);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        $view = $this->createViewForHttpNoContentResponse();

        return $view;
    }
}
