<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Form\Type\PaginationType;
use AppBundle\Form\Model\Pagination;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
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
     * Return all countries with pagination
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @throws ServerInternalErrorException
     *
     * @ApiDoc(
     *     description="Return all countries",
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
                /** @var Pagination $paginator */
                $paginator = $form->getData();

                $countires = $countryRepository->findCountriesWithPagination($paginator);

                $view = $this->createViewForHttpOkResponse([
                    'countries' => $countires,
                    '_metadata' => [
                        'total'  => count($countires),
                        'limit'  => $paginator->getLimit(),
                        'offset' => $paginator->getOffset(),
                    ],
                ]);
            } else {
                $countires = $countryRepository->findAllCountries();

                $view = $this->createViewForHttpOkResponse([
                    'countries' => $countires,
                ]);
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
     *     description="Return country by slug",
     *     requirements={
     *          {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="Slug of country"}
     *      },
     *     section="Country",
     *     statusCodes={
     *          200="Returned when successful",
     *          404="Returned when sight not found",
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
                    'message' => 'Not Found',
                ]);

                return $this->handleView($view);
            }

            $view = $this->createViewForHttpOkResponse([
                'country' => $country,
            ]);

            return $this->handleView($view);
        } catch (\Exception $e) {
            $this->sendExceptionToRollbar($e);
            throw $this->createInternalServerErrorException();
        }
    }
}
