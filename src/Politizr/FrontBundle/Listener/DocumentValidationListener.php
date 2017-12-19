<?php
namespace Politizr\FrontBundle\Listener;

use Oneup\UploaderBundle\Event\ValidationEvent;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;

/**
 *
 * @see https://github.com/1up-lab/OneupUploaderBundle/blob/master/Resources/doc/custom_validator.md
 * @author Lionel Bouzonville
 */
class DocumentValidationListener
{
    /**
     *
     */
    public function onValidate(ValidationEvent $event)
    {
        $config  = $event->getConfig();
        $file    = $event->getFile();
        $type    = $event->getType();
        $request = $event->getRequest();

        dump('onValidate');

        // do some validations
        // throw new ValidationException('Sorry! Always false.');
    }
}