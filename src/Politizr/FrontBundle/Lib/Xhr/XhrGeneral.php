<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use StudioEcho\Lib\StudioEchoUtils;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\BoxErrorException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PMCguQuery;
use Politizr\Model\PMCgvQuery;
use Politizr\Model\PMCharteQuery;

use Politizr\FrontBundle\Form\Type\PDDirectType;

/**
 * XHR service for general management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class XhrGeneral
{
    private $securityTokenStorage;
    private $eventDispatcher;
    private $templating;
    private $formFactory;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $eventDispatcher,
        $templating,
        $formFactory,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;

        $this->logger = $logger;
    }

    /**
     * Show/Hide suggestion timeline by default
     * beta
     */
    public function showSuggestion(Request $request)
    {
        // $this->logger->info('*** showSuggestion');

        // Request arguments
        $show = $request->get('show');
        // $this->logger->info('$show = ' . print_r($show, true));
        
        if ($show == "true") {
            $show = true;
        } else {
            $show = false;
        }
        $request->getSession()->set('showSuggestion', $show);

        return true;
    }

    /**
     * Direct message send
     * beta
     */
    public function directMessageSend(Request $request)
    {
        // $this->logger->info('*** directMessageSend');

        // Request arguments
        // $this->logger->info('$formTypeId = '.print_r($formTypeId, true));

        $form = $this->formFactory->create(new PDDirectType());

        $form->bind($request);
        if ($form->isValid()) {
            $directMessage = $form->getData();
            $directMessage->save();

            // Envoi email
            $dispatcher =  $this->eventDispatcher->dispatch('direct_message_email', new GenericEvent($directMessage));
        } else {
            $errors = StudioEchoUtils::getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }

}
