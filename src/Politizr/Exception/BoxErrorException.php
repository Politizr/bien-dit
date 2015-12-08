<?php

namespace Politizr\Exception;

/**
 * Throw to display front box error message
 *
 * @author Lionel Bouzonville
 */
class BoxErrorException extends \Exception implements BoxErrorExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
