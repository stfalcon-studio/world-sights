<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sight;
use AppBundle\Form\Model\Pagination;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Type\SightPhotoType;
use AppBundle\Entity\SightPhoto;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Sight Photo Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_sight_photos_")
 * @Rest\Prefix("/v1/sight-photos")
 */
class SightPhotoController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Return all sight photos
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return all sight photos",
     *     section="Sight Photo",
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
            $sightPhotoRepository = $this->getDoctrine()->getRepository('AppBundle:SightPhoto');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $sightPhotos = $sightPhotoRepository->findSightPhotosWithPagination($pagination);

                /** @var SightPhoto $sightPhoto */
                foreach ($sightPhotos as $sightPhoto) {
                    $url = $this->get('app.sight_photo')->getPathImage($sightPhoto);
                    $sightPhoto->setPhotoPath($url);
                }

                $view = $this->createViewForHttpOkResponse([
                    'sight_photos' => $sightPhotos,
                    '_metadata'    => [
                        'total'  => count($sightPhotos),
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_photo']));
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
     * Return sight photo
     *
     * @param SightPhoto $sightPhoto Sight photo
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return sight photo",
     *     section="Sight Photo",
     *     statusCodes={
     *          200="Returned when successful",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{id}")
     * @ParamConverter("id", class="AppBundle:SightPhoto")
     */
    public function getAction(SightPhoto $sightPhoto)
    {
        try {
            $url = $this->get('app.sight_photo')->getPathImage($sightPhoto);
            $sightPhoto->setPhotoPath($url);

            $view = $this->createViewForHttpOkResponse([
                'sight_photo' => $sightPhoto,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_photo']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Return sight photos by sight
     *
     * @param Sight $sight Sight
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Return sight photos by sight",
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
     * @Rest\Get("/sights/{slug}")
     *
     * @ParamConverter("sight", class="AppBundle:Sight")
     */
    public function getSightAction(Sight $sight)
    {
        try {
            $sightPhotos = $sight->getSightPhotos()->toArray();

            /** @var SightPhoto $sightPhoto */
            foreach ($sightPhotos as $sightPhoto) {
                $url = $this->get('app.sight_photo')->getPathImage($sightPhoto);
                $sightPhoto->setPhotoPath($url);
            }

            $view = $this->createViewForHttpOkResponse([
                'sight_photos' => $sightPhotos,
            ]);
            $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_photo']));
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }

    /**
     * Create sight photo
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *      section="Sight Photo",
     *      description="Create a new sight photo",
     *      input="AppBundle\Form\Type\SightPhotoType",
     *      output={
     *          "class"="AppBundle\Entity\SightPhoto",
     *          "groups"={"sight_photo"}
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
        try {
            $form = $this->createForm(SightPhotoType::class);

            $form->submit($request->request->all());
            if ($form->isValid()) {
                /** @var File $file */
                $file = $request->files->get('photo_file');
                if (null === $file) {
                    $form->get('photo_file')->addError(new FormError('photo_file is required field'));
                }
                /** @var SightPhoto $sightPhoto */
                $sightPhoto = $form->getData();

                $sightPhoto->setPhotoFile($file);

                $em = $this->getDoctrine()->getManager();
                $em->persist($sightPhoto);
                $em->flush();

                $url = $this->get('app.sight_photo')->getPathImage($sightPhoto);
                $sightPhoto->setPhotoPath($url);

                $view = $this->createViewForHttpOkResponse([
                    'sight_photo' => $sightPhoto,
                ]);
                $view->setSerializationContext(SerializationContext::create()->setGroups(['sight_photo']));
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
     * Delete sight photo
     *
     * @param SightPhoto $sightPhoto Sight photo
     *
     * @return Response
     *
     * @ApiDoc(
     *       requirements={
     *          {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="ID of sight photo"}
     *      },
     *      section="Sight Photo",
     *      statusCodes={
     *          204="Returned when successful",
     *          500="Returned when an error has occurred",
     *      }
     * )
     *
     * @Rest\Delete("/{id}")
     *
     * @ParamConverter("id", class="AppBundle:SightPhoto")
     */
    public function deleteAction(SightPhoto $sightPhoto)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sightPhoto);

            $em->flush();
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->createViewForHttpNoContentResponse();
    }
}
