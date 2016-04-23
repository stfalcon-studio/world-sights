<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * ServerInternalErrorException
 *
 * @author Yevgeniy Zholkevskiy <blackbullet@i.ua>
 */
class ServerInternalErrorException extends HttpException
{
    /**
     * Constructor
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, $message, $previous, [], $code);
    }
}
