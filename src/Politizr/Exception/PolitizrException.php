<?php

namespace Politizr\Exception;

/**
 * Politizr exception
 *
 * @author Lionel Bouzonville
 */
class PolitizrException extends \Exception implements PolitizrExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
