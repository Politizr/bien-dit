<?php

namespace Politizr\Exception;

class InconsistentDataException extends \Exception implements InconsistentDataExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}