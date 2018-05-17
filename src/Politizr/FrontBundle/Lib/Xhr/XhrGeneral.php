<?php
namespace Politizr\FrontBundle\Lib\Xhr;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\FrontBundle\Lib\Tools\StaticTools;

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
    private $globalTools;
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @event_dispatcher
     * @param @templating
     * @param @form.factory
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $eventDispatcher,
        $templating,
        $formFactory,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->eventDispatcher = $eventDispatcher;

        $this->templating = $templating;
        $this->formFactory = $formFactory;

        $this->globalTools = $globalTools;

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
     * Hide OP slide
     * beta
     */
    public function hideOp(Request $request)
    {
        // $this->logger->info('*** hideOp');

        // Request arguments
        $request->getSession()->set('showOp', false);

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
            $errors = $this->globalTools->getAjaxFormErrors($form);
            throw new BoxErrorException($errors);
        }

        return true;
    }

}
