<?php

namespace Politizr\Exception;

/**
 *
 * @author Lionel Bouzonville
 */
class SendEmailException extends PolitizrException implements SendEmailExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
