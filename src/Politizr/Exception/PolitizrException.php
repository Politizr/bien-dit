<?php

namespace Politizr\Exception;

/**
 * Politizr exception
 *
 * @author Lionel Bouzonville
 */
class PolitizrException extends \Exception implements PolitizrExceptionInterface
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
