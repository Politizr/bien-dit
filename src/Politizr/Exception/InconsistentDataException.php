<?php

namespace Politizr\Exception;

/**
 *
 * @author Lionel Bouzonville
 */
class InconsistentDataException extends PolitizrException implements InconsistentDataExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
