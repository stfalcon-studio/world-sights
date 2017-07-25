<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Model\Pagination;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Country Controller
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 *
 * @Rest\NamePrefix("api_country_")
 * @Rest\Prefix("/v1/countries")
 */
class CountryController extends FOSRestController
{
    use ControllerHelperTrait, RollbarHelperTrait;

    /**
     * Get all countries
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get all countries",
     *     section="Country",
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
            $countryRepository = $this->getDoctrine()->getRepository('AppBundle:Country');

            $form = $this->createForm(PaginationType::class);

            $form->submit($request->query->all());
            if ($form->isValid()) {
                /** @var Pagination $pagination */
                $pagination = $form->getData();

                $countries = $countryRepository->findCountriesWithPagination($pagination);
                $total     = $countryRepository->getTotalNumberOfEnabledCountries();

                $view = $this->createViewForHttpOkResponse([
                    'countries' => $countries,
                    '_metadata' => [
                        'total'  => $total,
                        'limit'  => $pagination->getLimit(),
                        'offset' => $pagination->getOffset(),
                    ],
                ]);
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
     * Get country by slug
     *
     * @param Country $country Country
     *
     * @return Response
     *
     * @ApiDoc(
     *     description="Get country by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of country"}
     *      },
     *     section="Country",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when country not found",
     *          500="Returned when internal error on the server occurred"
     *      }
     * )
     *
     * @Rest\Get("/{slug}")
     *
     * @ParamConverter("country", class="AppBundle:Country")
     */
    public function getAction(Country $country)
    {
        try {
            if (!$country->isEnabled()) {
                $view = $this->createViewForHttpNotFoundResponse([
                    'message' => 'Country not found',
                ]);

                return $this->handleView($view);
            }

            $view = $this->createViewForHttpOkResponse([
                'country' => $country,
            ]);
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }

        return $this->handleView($view);
    }
}
