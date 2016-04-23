<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ServerInternalErrorException;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * ControllerHelperTrait
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
trait ControllerHelperTrait
{
    /**
     * Create view for HTTP_OK (200) response
     *
     * @param array $data Response data
     *
     * @return View
     */
    protected function createViewForHttpOkResponse(array $data)
    {
        $data = array_merge(['code' => Response::HTTP_OK], $data);

        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * Create view for HTTP_CREATED (201) response
     *
     * @param array $data Response data
     *
     * @return View
     */
    protected function createViewForHttpCreatedResponse(array $data)
    {
        $data = array_merge(['code' => Response::HTTP_CREATED], $data);

        return $this->view($data, Response::HTTP_CREATED);
    }

    /**
     * Create view for HTTP_NO_CONTENT (204) response
     *
     * @return View
     */
    protected function createViewForHttpNoContentResponse()
    {
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create view for HTTP_BAD_REQUEST (400) response for invalid data
     *
     * @param array $data Response data
     *
     * @return View
     */
    protected function createViewForInvalidErrorResponse(array $data)
    {
        $data = array_merge(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Invalid data'], $data);

        return $this->view($data, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create view for HTTP_BAD_REQUEST (400) response for form validation error
     *
     * @param FormInterface $form Form
     *
     * @return View
     */
    protected function createViewForValidationErrorResponse(FormInterface $form)
    {
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Returns a ServerInternalErrorException
     *
     * This will result in a 500 response code
     *
     * @param string          $message  A message
     * @param \Exception|null $previous The previous exception
     *
     * @return ServerInternalErrorException
     */
    protected function createInternalServerErrorException($message = 'Internal Server Error', \Exception $previous = null)
    {
        return new ServerInternalErrorException($message, $previous);
    }
}
