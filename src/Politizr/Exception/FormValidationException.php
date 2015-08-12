<?php

namespace Politizr\Exception;

/**
 * @todo refactoring rename to ~ BoxErrorException
 *
 * @author Lionel Bouzonville
 */
class FormValidationException extends \Exception implements FormValidationExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
